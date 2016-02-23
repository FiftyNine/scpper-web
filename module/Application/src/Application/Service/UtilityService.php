<?php

namespace Application\Service;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\Http\Header\SetCookie;
use Zend\View\Model\ViewModel;
use Application\Model\SiteInterface;
use Application\Form\SearchForm;

class UtilityService implements UtilityServiceInterface
{
    /**#@+
     * @const int Default site id (english wiki)
     */
    const ENGLISH_SITE_ID = 66711;
    
    const SITE_ID_COOKIE = 'SiteId';
    /**#@-*/            
    
    /**
     *
     * @var SiteServiceInterface
     */
    protected $siteService;
    
    /**
     *
     * @var int
     */
    protected $siteId;
       
    /**
     *
     * @var AdapterInterface
     */
    protected $dbAdapter;
    
    /**
     *
     * @var type 
     */
    protected $searchForm;            
    
    public function __construct(
            SiteServiceInterface $siteService,             
            AdapterInterface $dbAdapter,
            SearchForm $searchForm,
            $siteId
    )
    {
        $this->siteService = $siteService;
        $this->dbAdapter = $dbAdapter;
        $this->searchForm = $searchForm;
        $this->siteId = $siteId;
    }  
    
    /**
     * 
     * {@inheritDoc}
     */
    public function selectSite(Request $request, Response $response)
    {
        if (!$request->isGet()) {
            return false;
        }
        $siteId = $request->getQuery('siteId', self::ENGLISH_SITE_ID);
        $site = $this->siteService->find($siteId);
        if (!$site) {
           $siteId = self::ENGLISH_SITE_ID; 
        }
        // Just in case
        $this->siteId = $siteId;
        $cookie = new SetCookie(self::SITE_ID_COOKIE, $siteId, time()+30*24*60*60); // now + 1 month
        $response->getHeaders()->addHeader($cookie);
        return true;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * 
     * @param \Application\Service\ViewModel $viewModel
     */
    public function attachSearchForm(ViewModel $viewModel)
    {
        $viewModel->setVariables(array(
            'searchForm' => $this->searchForm
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getSearchForm()
    {
        return $this->searchForm;
    }
    
    /**
     * Generates PHP files with field names as constants
     */
    public function generateDbConstants()
    {
        $metadata = new \Zend\Db\Metadata\Metadata($this->dbAdapter);
        $schema = $this->dbAdapter->getDriver()->getConnection()->getCurrentSchema();
        $tables = $metadata->getTableNames($schema, true);
        foreach ($tables as $table) {
            $words = explode('_', $table);
            $class = 'Db';
            foreach ($words as $word) {
                $word[0] = strtoupper($word[0]);
                $class = $class.$word;
            }            
            $filename = __DIR__.'/../Utils/DbConsts/'.$class.'.php';
            if (file_exists($filename))
                unlink($filename);
            $writer = new \Zend\Log\Writer\Stream($filename);
            $writer->setFormatter(new \Zend\Log\Formatter\Simple('%message%'));
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info('<?php');
            $logger->info('');
            $logger->info('namespace Application\Utils\DbConsts;');
            $logger->info('');
            $logger->info("class {$class}");
            $logger->info('{');
            $logger->info("    const TABLE = '{$table}';");
            $columns = $metadata->getColumnNames($table, $schema);
            foreach ($columns as $column) {
                $logger->info(vsprintf("    const %s = '%s';", array(strtoupper($column), $column)));
            }
            $logger->info('');
            $hasConst = '
    static public function hasField($field) 
    {
        if (!is_string($field)) {
            return false;
        }
        $field = strtoupper($field);
        $reflect = new \ReflectionClass(__CLASS__);
        foreach ($reflect->getConstants() as $name => $value) {
            if (strtoupper($value) === $field) {
                return true;
            }
        };
        return false;
    }';
            $logger->info($hasConst);
            $logger->info('}');
            $logger = null;
            chmod($filename, 0777);
        }        
    }
    
}