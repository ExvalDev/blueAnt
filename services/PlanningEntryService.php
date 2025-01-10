<?php 

require 'controllers/PlanningEntryController.php';


class PlanningEntryService{

    private $planningEntriesController;
    private $config;
    private static $planningEntries = null;

    public function __construct()
    {

   
        $this->planningEntriesController = new PlanningEntryController();
    }


    function getPlanningEntriesByProjectId($id){

        if (self::$planningEntries === null ) {
            self::$planningEntries = [];
            $responses = $this->planningEntriesController->getPlanningEntriesByCityId($id);

            foreach ($responses as $response) {
                self::$planningEntries[$response['id']] = new PlanningEntry(
                        $response['id'],
                        $response['description'],
                        $response['start'],
                        $response['end']
                );
            }
        }
        return self::$planningEntries;
    }
}