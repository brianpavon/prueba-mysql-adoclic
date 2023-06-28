<?php 
include_once "DB.php";

class UserStats
{
    public $full_name;
    public $total_views;
    public $total_clicks;
    public $total_conversions;
    public $cr;
    public $last_date;

    public function getStats($dateFrom,$dateTo, $totalClicks = null){

        if(!$this->isValidDate($dateFrom)){
            return "Fecha desde inválida, debe ser yyyy-mm-dd";
        }
        
        if(!$this->isValidDate($dateTo)){
            return "Fecha hasta inválida, debe ser yyyy-mm-dd";
        }

        $sql = "SELECT 
                CONCAT(u.first_name, ' ', u.last_name) AS full_name,
                SUM(us.views) AS total_views,
                SUM(us.clicks) AS total_clicks,
                SUM(us.conversions) AS total_conversions,
                ROUND((SUM(us.conversions) / SUM(us.clicks)) * 100, 2) AS cr,
                MAX(DATE_FORMAT(us.date, '%Y-%m-%d')) AS last_date
            FROM 
                users u
                INNER JOIN user_stats us ON u.id = us.user_id
            WHERE 
                u.status = 'active'
                AND us.date >= DATE('$dateFrom') AND us.date < DATE_ADD(DATE('$dateTo'), INTERVAL 1 DAY)
            GROUP BY u.id";

        if($totalClicks){
            $sql = $sql . " HAVING total_clicks >= 9000";
        }

        $objDB = Database::objDB();
        $query = $objDB->getQuery($sql);
        $query->execute();
        $resultSQL = $query->fetchAll(PDO::FETCH_CLASS, "UserStats");
        if(count($resultSQL) > 0){
            return $resultSQL;
        }else{
            return "No hay resultados con los filtros aplicados.";
        }
    }

    private function isValidDate($date) {
        $tempDate = explode('-', $date);
        
        return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
    }
}