<?php

namespace Application\Mapper;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\EventManager\EventManagerAwareInterface;
use Application\Utils\Order;

class ZendDbSqlMapper implements SimpleMapperInterface, EventManagerAwareInterface
{
    // User trait to avoid implementing basic stuff
    use \Zend\EventManager\EventManagerAwareTrait;

    const DATETIME_FORMAT = 'Y-m-d H:i:s';
    const DATE_FORMAT = 'Y-m-d|';
    const GROUP_SUFFIX = '___GROUP';
    /**
     *
     * @var AdapterInterface
     */
    protected $dbAdapter;
    
    /**
     *
     * @var HydratorInterface
     */
    protected $hydrator;
    
    /**
     *
     * @var mixed
     */
    protected $objectPrototype;
    
    /**
     *
     * @var string
     */
    protected $table;
    
    /**
     * 
     * @var string
     */
    protected $idFieldName;
    
    
    /**
     * Sends event for a logger to write down query text
     * @param mixed $select
     */
    protected function logQuery($select)
    {
        if ($select instanceof Select) {
            $platform = $this->dbAdapter->getPlatform();
            $query = $select->getSqlString($platform);
        } else {
            $query = json_encode($select);
        }
        $this->getEventManager()->trigger(\Application\Utils\Events::LOG_SQL_QUERY, $this, compact('query'));               
    }
    
    /**
     * Returns result of a query if it's successful and false otherwise
     * @param Sql $sql
     * @param Select $select
     * @param int $offset
     * @param int $limit
     * @return boolean|ResultInterface
     */
    protected function fetch(Sql $sql, Select $select, $offset = 0, $limit = 0)
    {
        $this->logQuery($select);
        if ($offset > 0) {
            $select->offset($offset);
        }
        if ($limit > 0) {
            $select->limit($limit);
        }                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $profiler = $this->dbAdapter->getProfiler();
        if ($profiler) {
            $profiler->profilerStart($stmt);
            $result = $stmt->execute();
            $profile = $profiler->getLastProfile();
            $this->logQuery($profile);
        } else {
            $result = $stmt->execute();
        }
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            return $result;
        }        
        return false;
    }

    /**
     * Returns a single object created from query
     * @param Sql $sql
     * @param Select $select
     */
    protected function fetchObject(Sql $sql, Select $select)
    {
        $result = $this->fetch($sql, $select);
        if ($result && $result->getAffectedRows() === 1) {
          return $this->hydrator->hydrate($result->current(), $this->objectPrototype);
        }
        throw new \InvalidArgumentException("Object not found or more than one object found");
    }
    
    /**
     * Returns a collection of hydrated objects or false on error
     * @param Sql $sql
     * @param Select $select
     * @param type $offset
     * @param type $limit
     * @return boolean|ResultSet
     */
    protected function fetchResultSet(Sql $sql, Select $select, $offset = 0, $limit = 0)
    {
        $result = $this->fetch($sql, $select, $offset, $limit);
        if ($result) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->objectPrototype);            
            return $resultSet->initialize($result);            
        }
        return false;
    }


    /**
     * Returns result of a query as an array of associative arrays
     * @param Sql $sql
     * @param Select $select
     * @return array(array('Name' => 'Value'))
     */
    protected function fetchArray(Sql $sql, Select $select)
    {
        $dataset = $this->fetch($sql, $select);
        $result = array();
        while ($dataset && $dataset->current()) {
            $result[] = $dataset->current();
            $dataset->next();
        }
        // Convert date strings into DateTime objects
        if ($dataset->getResource() instanceof \PDOStatement) {
            $utcTimeZone = new \DateTimeZone('UTC');
            for ($i=0; $i<$dataset->getFieldCount(); $i++) {
                $column = $dataset->getResource()->getColumnMeta($i);
                if ($column['native_type'] === 'DATE') {
                    foreach ($result as &$row) {
                        $row[$column['name']] = \DateTime::createFromFormat(self::DATE_FORMAT, $row[$column['name']], $utcTimeZone);
                    }
                }
            }
        }
        return $result;                
    }
    
    /**
     * Returns number of records in query
     * 
     * @param Sql $sql
     * @param Select $select
     * @return int
     */
    protected function fetchCount(Sql $sql, Select $select)
    {
        $aggregate = new \Application\Utils\Aggregate('*', \Application\Utils\Aggregate::COUNT, 'Number');
        $this->aggregateSelect($select, array($aggregate));
        $array = $this->fetchArray($sql, $select);
        if (count($array) > 0) {
            return $array[0]['Number'];
        }
        return 0;        
    }   
        

    /**
     * Builds a new aggregated select from normal select
     * @param \Zend\Db\Sql\Select $select
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @return Select
     */
    protected function aggregateSelect(Select $select, $aggregates)
    {
        $columns = array();
        foreach ($aggregates as $aggregate) {
            $aggName = $aggregate->getAggregateName();
            if ($aggregate->getGroup()) {                
                if ($aggName) {
                    $select->group($aggName);                    
                } else {
                    $select->group($aggregate->getAggregateExpression());
                }
            }
            if ($aggName) {
                $columns[$aggName] = $aggregate->getAggregateExpression();
            } else {
                $columns[] = $aggregate->getAggregateExpression();
            }
        }
        $select->columns($columns, false);    
        return $select;
    }    

    protected function orderSelect(Select $select, $order)
    {
        $newOrder = array(); // We run this place now
        foreach ($order as $name => $type) {
            $newOrder[] = $name.' '.Order::getTypeSql($type);
        }
        $select->order($newOrder);
        return $select;
    }


    /**
     * Returns paginator object encapsulating a select query
     * @param Select $select
     * @return \Zend\Paginator\Paginator
     */
    protected function getPaginator(Select $select, $asArray = false)
    {
        $resultSet = null;
        if (!$asArray) {
            $resultSet = new \Zend\Db\ResultSet\HydratingResultSet($this->hydrator, $this->objectPrototype);
        }
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        return $paginator;        
    }
    
    /**
     * 
     * @param AdapterInterface $dbAdapter
     * @param HydratorInterface $hydrator
     * @param Object $objectPrototype
     * @param string $table
     * @param string $idFieldName
     */
    public function __construct(
            AdapterInterface $dbAdapter, 
            HydratorInterface $hydrator, 
            $objectPrototype,
            $table,
            $idFieldName)
    {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->objectPrototype = $objectPrototype;
        $this->table = $table;
        $this->idFieldName = $idFieldName;       
    }    
    
    /**
     * 
     * {@inheritDoc}
     */
    public function find($id)
    {
        $sql = new Sql($this->dbAdapter);        
        $select = $sql->select($this->table);
        $select->where(array(
            "{$this->idFieldName} = ?" => $id
        ));
        
        $result = $this->fetch($sql, $select);
        if ($result && $result->getAffectedRows()) {
          return $this->hydrator->hydrate($result->current(), clone $this->objectPrototype);
        }
        try {
            return $this->fetchObject($sql, $select);
        } catch (\InvalidArgumentException $ex) {
            throw new \InvalidArgumentException("Object of table {$this->table} with id = {$id} not found");
        }        
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function findAll($paginated = true) {
        $sql = new Sql($this->dbAdapter);        
        $select = $sql->select($this->table);
        if ($paginated) {
            $result = $this->getPaginator($select);
        } else {
            $result = $this->fetchResultSet($sql, $select);
        }
        if (!$result) {
            $result = array();
        }                    
        return $result;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function countAll()
    {
        $sql = new Sql($this->dbAdapter);        
        $select = $sql->select($this->table);
        return $this->fetchCount($sql, $select);
    }
}


