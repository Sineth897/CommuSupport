<link rel="stylesheet" href="/CommuSupport/public/CSS/statistics/charts/charts.css">

<script
    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

<?php
$dsn = $config['dsn'] ?? '';
$user = $config['user'] ?? '';
$password = $config['password'] ?? '';

$pdo = new \PDO('mysql:host=127.0.0.1;port=3306;dbname=commusupport_db;', 'root', '');
//        $this->pdo = new \PDO($dsn, $user, $password);
?>

<?php
// Fetch the data from the database
$urgencies = array("Within 7 days", "Within a month");
$results = array();

foreach ($urgencies as $urgency) {
    $sql = "SELECT COUNT(*) as count, MONTHNAME(postedDate) as month FROM request WHERE urgency = :urgency GROUP BY MONTH(postedDate)";
    $statement = $pdo->prepare($sql);
    $statement->execute(array(":urgency" => $urgency));
    $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
    // Pass the data to the JavaScript file
    $monthsOfYear = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $chartData = array_fill_keys($monthsOfYear, 0);
    foreach ($result as $row) {
        $chartData[$row['month']] = $row['count'];
    }
    $results[$urgency] = $chartData;
}

var_dump($results[$urgencies[0]]);
?>

<canvas id="myChart"></canvas>

<script>

    const weekData = <?php echo json_encode($results[$urgencies[0]]); ?>;
    const monthData = <?php echo json_encode($results[$urgencies[1]]); ?>;
</script>

<script src="/CommuSupport/public/js/charts/bar.js"></script>

