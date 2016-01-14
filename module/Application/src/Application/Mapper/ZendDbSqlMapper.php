<?php

namespace Application\Mapper;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\EventManager\EventManagerAwareInterface;
use Application\Utils\DateGroupType;

class ZendDbSqlMapper implements SimpleMapperInterface, EventManagerAwareInterface
{
    // User trait to avoid implementing basic stuff
    use \Zend\EventManager\EventManagerAwareTrait;
    
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
     * @param Select $select
     */
    protected function logQuery(Select $select)
    {
        $platform = $this->dbAdapter->getPlatform();
        $query = $select->getSqlString($platform);
        $this->getEventManager()->trigger(\Application\Utils\Events::LOG_SQL_QUERY, $this, compact('query'));               
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
        $result = $stmt->execute();        
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            return $result;
        }        
        return false;
    }

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
     * Returns number of records in query
     * 
     * @param Sql $sql
     * @param Select $select
     * @return int
     */
    protected function fetchCount(Sql $sql, Select $select)
    {
        $select->columns(array('num' => new Expression('COUNT(*)')));
        $this->logQuery($select);
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            return $result->current()['num'];
        }
        return 0;        
    }
    
    /**
     * 
     * 
     */
    protected function fetchCountGroupedByDate(Sql $sql, Select $select, $dateName, $groupBy)
    {
        $groupDate = 'GroupDate';
        $number = 'Number';
        $group = DateGroupType::getSqlGroupString($groupBy, $dateName);
        $groupSelect = $sql->select()
                ->from(array('tmp' => $select))
                ->columns(array(
                    $number => new Expression('COUNT(*)'),
                    $groupDate => new Expression($group)
                ))
                ->group($groupDate)
                ->order($groupDate.' ASC');
        $dataset = $this->fetch($sql, $groupSelect);
        $utcTimeZone = new \DateTimeZone('UTC');
        $result = array();
        while ($dataset && $dataset->current()) {
            $result[] = array(
                \DateTime::createFromFormat('Y-m-d|', $dataset->current()[$groupDate], $utcTimeZone),
                (int) $dataset->current()[$number]
            );
            $dataset->next();
        }
        return $result;        
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
          return $this->hydrator->hydrate($result->current(), $this->objectPrototype);
        }
        throw new \InvalidArgumentException("Object of table {$this->table} with id = {$id} not found");
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function findAll($offset = 0, $limit = 0) {
        $sql = new Sql($this->dbAdapter);        
        $select = $sql->select($this->table);
        $result = $this->fetchResultSet($sql, $select, $offset, $limit);
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


