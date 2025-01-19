<?php 
/**
 * Service class to handle Planninentry actions
 */
require 'controllers/PlanningEntryController.php';
require 'models/PlanningEntry.php';



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

            usort($responses, function ($a, $b) {
                $dateA = new DateTime($a['end']);
                $dateB = new DateTime($b['end']);
                
                if ($dateA < $dateB) {
                    return -1; 
                } elseif ($dateA > $dateB) {
                    return 1; 
                } else {
                    return 0; 
                }

            });
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