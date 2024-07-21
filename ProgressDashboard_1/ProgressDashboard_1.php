<?php
include '../dbConnection.php';
include '../fetchKPIs.php';


//QUERY Fetch overall learners completion status (half pie chart)
$sql = "SELECT 
SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS total_completed,
SUM(CASE WHEN status = 'incompleted' THEN 1 ELSE 0 END) AS total_incompleted
FROM Course;";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_completed = $row["total_completed"];
    $total_incompleted = $row["total_incompleted"];
} else {
    $total_completed = 0;
    $total_incompleted = 0;
}



//QUERY Fetch learners completion status by domain (bar chart)
$sql2 = "SELECT Domain, COUNT(*) AS completed_count FROM Learner WHERE user_id IN ( SELECT user_id FROM Course WHERE status = 'completed' ) GROUP BY Domain;";

$result2 = $conn->query($sql2);

// Fetch data from SQL result
$data = array();
while ($row = $result2->fetch_assoc()) {
    $domain = $row['Domain'];
    $completed_count = $row['completed_count'];
    $data[$domain] = $completed_count;
}

// Convert fetched data to JavaScript object
$learnersCompletionData = json_encode(array_values($data));

//QUERY Fetch learners completion status by domain (bar chart)
$sql3 = "SELECT YEAR(uc.enrolment_date) AS year, 
                MONTH(uc.enrolment_date) AS month, 
                SUM(CASE WHEN uc.status = 'completed' THEN 1 ELSE 0 END) AS completed_count, 
                SUM(CASE WHEN uc.status != 'completed' THEN 1 ELSE 0 END) AS incompleted_count 
         FROM Learner l 
         JOIN Course uc ON l.user_id = uc.user_id 
         WHERE l.Domain = 'HR' 
         GROUP BY YEAR(uc.enrolment_date), MONTH(uc.enrolment_date) 
         ORDER BY year, month 
         LIMIT 0, 25;";

$result3 = $conn->query($sql3);

$completed_counts = array_fill(0, 12, 0);
$incompleted_counts = array_fill(0, 12, 0);

while ($row = $result3->fetch_assoc()) {
    $month = intval($row['month']) - 1; // Adjust month to 0-indexed for JavaScript
    $completed_counts[$month] = intval($row['completed_count']);
    $incompleted_counts[$month] = intval($row['incompleted_count']);
}

$HRCompleted = json_encode($completed_counts);
$HREnrolled = json_encode($incompleted_counts);


//QUERY Fetch learners completion status by domain (bar chart)
$sql4 = "SELECT YEAR(uc.enrolment_date) AS year, 
                MONTH(uc.enrolment_date) AS month, 
                SUM(CASE WHEN uc.status = 'completed' THEN 1 ELSE 0 END) AS completed_count, 
                SUM(CASE WHEN uc.status != 'completed' THEN 1 ELSE 0 END) AS incompleted_count 
         FROM Learner l 
         JOIN Course uc ON l.user_id = uc.user_id 
         WHERE l.Domain = 'MoE' 
         GROUP BY YEAR(uc.enrolment_date), MONTH(uc.enrolment_date) 
         ORDER BY year, month 
         LIMIT 0, 25;";

$result4 = $conn->query($sql4);

$MoEcompleted_counts = array_fill(0, 12, 0);
$MoEincompleted_counts = array_fill(0, 12, 0);

while ($row = $result4->fetch_assoc()) {
    $month = intval($row['month']) - 1; // Adjust month to 0-indexed for JavaScript
    $MoEcompleted_counts[$month] = intval($row['completed_count']);
    $MoEincompleted_counts[$month] = intval($row['incompleted_count']);
}

$MoECompleted = json_encode($MoEcompleted_counts);
$MoeEnrolled = json_encode($MoEincompleted_counts);


//QUERY Fetch learners completion status by domain (bar chart)
$sql5 = "SELECT YEAR(uc.enrolment_date) AS year, 
                MONTH(uc.enrolment_date) AS month, 
                SUM(CASE WHEN uc.status = 'completed' THEN 1 ELSE 0 END) AS completed_count, 
                SUM(CASE WHEN uc.status != 'completed' THEN 1 ELSE 0 END) AS incompleted_count 
         FROM Learner l 
         JOIN Course uc ON l.user_id = uc.user_id 
         WHERE l.Domain = 'Defense' 
         GROUP BY YEAR(uc.enrolment_date), MONTH(uc.enrolment_date) 
         ORDER BY year, month 
         LIMIT 0, 25;";

$result5 = $conn->query($sql5);

$Defensecompleted_counts = array_fill(0, 12, 0);
$Defenseincompleted_counts = array_fill(0, 12, 0);

while ($row = $result5->fetch_assoc()) {
    $month = intval($row['month']) - 1; // Adjust month to 0-indexed for JavaScript
    $Defensecompleted_counts[$month] = intval($row['completed_count']);
    $Defenseincompleted_counts[$month] = intval($row['incompleted_count']);
}

$DefenseCompleted = json_encode($Defensecompleted_counts);
$DefenseEnrolled = json_encode($Defenseincompleted_counts);



//QUERY Fetch learners completion status by domain (bar chart)
$sql6 = "SELECT YEAR(uc.enrolment_date) AS year, 
                MONTH(uc.enrolment_date) AS month, 
                SUM(CASE WHEN uc.status = 'completed' THEN 1 ELSE 0 END) AS completed_count, 
                SUM(CASE WHEN uc.status != 'completed' THEN 1 ELSE 0 END) AS incompleted_count 
         FROM Learner l
         JOIN Course uc ON l.user_id = uc.user_id 
         WHERE l.Domain = 'Others' 
         GROUP BY YEAR(uc.enrolment_date), MONTH(uc.enrolment_date) 
         ORDER BY year, month 
         LIMIT 0, 25;";

$result6 = $conn->query($sql6);

$Otherscompleted_counts = array_fill(0, 12, 0);
$Othersincompleted_counts = array_fill(0, 12, 0);

while ($row = $result6->fetch_assoc()) {
    $month = intval($row['month']) - 1; // Adjust month to 0-indexed for JavaScript
    $Otherscompleted_counts[$month] = intval($row['completed_count']);
    $Othersincompleted_counts[$month] = intval($row['incompleted_count']);
}

$OthersCompleted = json_encode($Otherscompleted_counts);
$OthersEnrolled = json_encode($Othersincompleted_counts);











?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="progressDashboard_1.css">
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.1/dist/echarts.min.js"></script>

</head>

<body>
    <div class="AimsRegistrationDashboard">
    </div>
    <div class="Group1171278554">
        <div class="kpi-container">
            <div class="kpi-item">
                <div class="icon Verify1"><svg width="35" height="35" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <circle opacity="0.1" cx="35" cy="35" r="35" fill="#E33993" />
                        <mask id="mask0_6_3064" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="19" y="18" width="34" height="34">
                            <rect x="19" y="18" width="34" height="34" fill="url(#pattern0_6_3064)" />
                        </mask>
                        <g mask="url(#mask0_6_3064)">
                            <rect x="-35" y="-6" width="108" height="96" fill="#E33993" />
                        </g>
                        <defs>
                            <pattern id="pattern0_6_3064" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_6_3064" transform="scale(0.00195312)" />
                            </pattern>
                            <image id="image0_6_3064" width="512" height="512" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAgAAAAIACAYAAAD0eNT6AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAN1wAADdcBQiibeAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAACAASURBVHic7d17uGV1fef59/eACCoXGxCoJmYgaU2amTCjJAbGREWDhYEAbRSTmEi3baIZ03E6xmfS6UfEp5mOHZKYiBMTL4lhZjStpihAxbRG0l5ALJJA63Rre0UtICBXjSWX+s4fa+/i1OHUrnPZe3/3Wr/363nOA5an9vpwO9/P/q6114rMZFFExKOAE4DjgC2jr+NW/HEL8LiqjJIgM2Pex4yI64Cnjf7nXcAtwM4Vfxz/+dcy86vzzij1yYHVASLiUGArcC7wPOCI2kSSeuDxo69/uq9viIgvAleOvj6WmQ/MKZvUC1GxAYiI44Cz6Yb+6cCj5x5C0oYtwAZgve4BPkRXBj6QmXdOLZjUU3MrABFxCPBLwPl0/xHP/QeIpOnoYQFY7iHgk8A7gMsy86EpvKbUO0uzPkBELEXEvwA+D/we8KM4/CXVOQD4MeBPgM9ExPOL80glZloAIuIs4Cbg7cDxszyWJG3ADwDvjYhPR8RPVIeR5mkmBSAinhYR19CdbztpFseQpCk6BfjLiPhIREzjNIO08KZaACLieyLiPcB1wDOm+dqSNAenA9dFxLaI+J7qMNIsTa0ARMRpwA7gp6f1mpJU5FxgR0Q8vTqINCtTKQARcQHwUeAJ03g9SVoATwA+EhEvqw4izcKmCsDoCv9L6K6mPWg6kSRpYRwE/HFEvDkiym+cJk3ThgtARBwGXAX82vTiSNJC+mXgwxFxVHUQaVo2VAAi4vvpLvQ7c7pxJGlhPYPuuoCTq4NI07DuArBs+P/g9ONI0kL7XuATEXF6dRBps9ZVAEZr/yuAI2cTR5IW3mOBKy0B6rs1F4CIWALeje/8JekxWALUc+vZAPwHPOcvSWOPAa6yBKiv1lQARp/z92p/SdrbIVgC1FP7LQCjO/z90RyySFIfWQLUSxMLwOhe2NvwJj+SNMm4BDy7Ooi0VvvbAPwu3t5XktbiELoLAy0B6oV9FoDRIzF9sI8krZ0lQL0xaQPwhrmlkKThsASoF1YtABFxFt1tLyVJ62cJ0MJ7RAEY3fDntwqySNKQWAK00FbbAFwAnDTnHJI0RJYALay9CkBEHAJcVJRFkobIEqCFtHID8EvA8RVBJGnAxiXgOdVBpLGVBeD8khSSNHyHAFdYArQo9hSAiDgOeFphFkkaOkuAFsbyDcDZQFQFkaRGWAK0EJYXgHPLUkhSWywBKrcEEBGHAj7JSpLmxxKgUuMNwFbg0ZVBJKlBlgCVGRcA1/+SVGNcAn6iOojashQRjwKeVx1EkhpmCdDcLQEnAEdUB5Gkxh2MJUBztARsqQ4hSQIsAZqjJeC46hCSpD0sAZoLNwCStHgsAZo5NwCStJgsAZopNwCStLjGJeCM6iAaHguAJC22g4HtlgBNm6cAJGnxWQI0dW4AJKkfLAGaqiXgcdUhJElrYgnQ1Czt/1skSQvEEqCpsABIUv9YArRpFgBJ6qdxCXhudRD1kwVAkvrrYOByS4A2wgIgSf1mCdCGWAAkqf8sAVo3C4AkDYMlQOtiAZCk4bAEaM0sAJI0LJYArYkFQJKGZ1wCnlMdRIvLAiBJw3Qw8J6IeFJ1EC0mC4AkDdcRdDcLOrw6iBaPBUCShu0HgHdFhD/vtRf/hZCk4TsT+K3qEFosFgBJasOvR8SLq0NocVgAJKkdb42IH64OocVgAZCkdow/HuhFgbIASFJjtgCvqQ6hehYASWrPqyLi2OoQqmUBkKT2PAZ4bXUI1bIASFKbXhYR318dQnUsAJLUpgOBf1cdQnUsAJLUrhdGxFOqQ6iGBUCS2hXAv68OoRoWAElq2xkRcXJ1CM2fBUCSdE51AM2fBUCSZAFokAVAkvSUiDi+OoTmywIgSQI4uzqA5ssCIEkCTwM0xwIgSQJ4VkQcWh1C82MBkCQBHAQ8tzqE5scCIEkaO7M6gObHAiBJGjuhOoDmxwIgSRo7rjqA5scCIEkaO7Y6gObHAiBJGjsiIg6uDqH5sABIkpZzC9AIC4AkaTkLQCMsAJKk5SwAjbAASJKW85MAjbAASJKWO6o6gObDAiBJWu6A6gCaDwuAJEkNCiCrQ0jql8yM6gySNscNgCRJDbIASJLUIAuAJEkNsgBIktQgC4AkSQ2yAEiS1CALgCRJDbIASJLUIAuAJEkNsgBIktQgC4AkSQ2yAEiS1CALgCRJDbIASJLUIAuApHWLiMdWZ5C0ORYASRtxbHUASZtjAZC0ERYAqecsAJI24uTqAJI2xwIgaSOeVx1A0uYEkNUhJPXOd4AjM/M71UEkbYwbAEkbcQjwi9UhJG2cGwBJG3Ub8H2Z+e3qIJLWzw2ApI06BvjN6hCSNsYNgKTNSOCnMvOq6iCS1scCIGmz7gGenpmfqQ4iae08BSBpsw4HPh4Rz64OImntLACSpuFw4IMR8WsRcVB1GEn75ykASdP2ReA3gG2Z+WB1GEmrswBImpW7gCuB9wNfBr4B3JaZD5WmkgRYACRJe7stM33YUwO8BkCSpAZZACRJapAFQJKkBlkAJElqkAVAkqQGWQAkSWqQBUCSpAZZACRJapAFQJKkBlkAJElqkAVAkqQGWQAkSWqQBUCSpAZZACRJapAFQJKkBlkAJElqkAVAkqQGWQAkSWqQBUCSpAZZACRJapAFQJKkBlkAJElqkAVAkqQGWQAkSWqQBUCSpAZZACRJapAFQJKkIhFxUEQcUHHsAysOKklSKyLieGAr8E+BY1d8PR7YHRHfBG5d9nUb8DfAhzLzzpnkAnIWLyxJ6qXbMvPY6hB9FhGPAp4OnDn6+h838XK7gU8BHxx93ZCZU5nbFoDV7QauBbYDO4CdwM7MvK80ldRzo1XnMcAW4AS6d0VnA0dX5tJeLAAbFBFHAq8BXgEcOqPD3AL8NvCHmblrMy9kAdjbLuBS4JLMvK06jNSCiFgCzgAuBp5SHEcWgHWLiCOAXwN+ldkN/pV2Av8n8NbMvH8jL2ABeNg24FWZeXN1EKlFERHA+XQl/MjiOC2zAKxRRDya7h3/vwaOKIpxM/D6zHz7en+jBaD767+I7m9g638vpHIRcSJwBXBSdZZGWQDWICL+Md0bxx+uzjLyXuCCzPz2Wn9D6wUggZdk5mXVQSQ9LCIOA64GTq3O0iALwH5ExGnA++iu4l8knwHOzcwvruWbW78PwEUOf2nxZOa9wHnA16uzSMtFxMuAj7J4wx+6Txt8OiKeu5ZvbrkAbANeXx1C0upGF+KeCzxYnUUCiIhLgD8GDqrOMsHjgQ9ExM/t7xtbLQC76C74a/n0h7TwMvMG4C3VOaSI+D26K/37YAl4W0T8yP6+qUWXerW/1BsXAfdUh1C7IuKNwKuqc6zTwcC2iDhuX9/QYgHYDVxSHULS2mTmHcC6P+IkTcNo+P9qdY4N2gL8xejjio/QYgG41pv8SL2zrTqA2tPz4T/2o+zjNFqLBWB7dQBJ6/ZJ4PbqEGpHRPw+/R/+YxdExE+u/MUWC8CO6gCS1iczdwM3VudQG0bD/19V55iyfze62+YeLRaAndUBJG3IrdUBNHwDHf4A/zPwwuW/YAGQ1BcWAM1URPwBwxz+Y68fPZETaLMASJK0l9Hw/5XqHDP2JOCC8f9osQBsqQ4gaUMW8darGoBGhv/Ya8Z/YgGQ1BcWAE1dRLyJdoY/wJMi4knQZgE4pTqApPWJiCXg5OocGpbR8H9ldY4CZ0GbBeCc6gCS1u004OjqEBqOhoc/NFwATo2IY6pDSFqX86oDaDgi4lLaHf4AT4+Iw1ssAEvAq6tDSFqbiDgKeGl1Dg3DaPj/b9U5ij0KOKPFAgDwyoh4YnUISWtyIXB4dQj1n8N/Lye1WgAOBt648raIkhZLRDwVeHl1DvVfRLwZh/9yx7VaAKA7p/ja6hCSVje6Vudy4MDqLOqv6LwZ+OXqLAum6QIAcGFE/Hx1CEl7i4jD6B4BfHx1FvXXaMt7KQ7/1TRfAAJ4Z0Rc6OkAaTFExIl0j/89tTqL+mv0M913/vvWfAGArgS8DnifFwZKdUar2hcB1wMnVedRfy0b/q+ozrLAjrYAPOw84HMR8dveJ0Can4hYioitwA7gXcCRxZHUfwcBT64OseDuDiCrUyyg3cC1wHa6H0o7gZ2ZeV9pKqnnRo8iPYbumRwnAFuBs/Euf4vk1sw8rjrEZkXEY4ArgdOrsyyoGy0AkqTlbsvMQTx4yRIw0dWeApAkDVJm/gPdhumvqrMsoFssAJKkwbIE7JMFQJI0bJaAVX3BAiBJWm6Q14VZAh7hQxYASdLYbuDfVIeYFUvAHn+XmTstAJIk6Ib/SzPzT6qDzJIlAIAPAFgAJEm7gX+RmX9aHWQeLAG8H7rb4A7yfI8kaU12A/88M/+sOsi8NXqfgDuBJ2TmQ24AJKldzQ5/aHYT8IeZ+RC4AZCkVu0GLsjMy6qDVGtoE3AXcGJm3g1eAyBJLdoNvMTh32loE/AfxsMf3ABIUmseohv+/091kEUz8E3ArcD3jcoO4AZAklryEPALDv/VDXwTcPHy4Q9uACSpFQ8BP5+Z76oOsugGuAm4DvjxzHxg+S9aACRp+B4CXpyZ764O0hcDKgE7gVMy85aV/4enACRp2B4Cfs7hvz4DOR2wCzh3teEPFgBJGrIH6Yb/n1cH6aMBlIBfzMxP7+v/tABI0jA5/KegxyXgd/b3MU8LgCQNz4PAz2bmf6wOMkl0fmV0vn1h9bAEvAP49f19kwVAkoblQeBnMvM91UEmiYgA3gz8AXCVJWBq3gH8y8zc7wX+fgpAkobjQeBFmfm+6iCTLBv+r1j2yx8Fzlr5WfVFs+CfDljz8Ac3AJI0FA8C5/d0+AM8CzcBm/F21jH8wQIgSUPwAN3w/4vqIJNMGP5jloCNeTvwsvUMf/AUgCT13Xj4b6sOMskahv9yng5Yuw0Nf3ADIEl99gDwwoENf3ATsFZvY4PDH9wASFJfPQC8IDO3VweZZAPDfzk3Afv2Nrob/Wx4hrsBkKT+uR/46YEPf3ATsC+bHv5gAZCkvrmf7p3/FdVBJpnC8B97FvB+S8Aeb2UKwx88BSBJfTJ+539ldZBJpjj8l7sG+MnGTwe8FfilaQx/sADsy27gWmA7sIPucYo7M/O+0lQanIg4ADgG2AKcAGylexdxdGUuLaT7gedn5lXVQSaZ0fAfu4Z2S8BUhz9YAFbaBVwKXJKZt1WHUZsiYgk4A7gYeEpxHC2G+4F/lpnvrw4yyYyH/9g1tFcC/hh4+TSHP1gAltsGvCozb64OIsGeH6bn05XSI4vjqM536Yb/B6qDTDKn4T92De2UgJkMf7AAQPfXfxHw+ln8DZY2KyJOBK4ATqrOorlz+O/bNQy/BMxs+IMFIIGX7O+ZyVK1iDgMuBo4tTqL5ua7wHmZ+cHqIJMUDf+xaxhuCfgj4BWzfGPa+scAL3L4qw8y817gPODr1Vk0F98FznX479czgQ9ExGOLjr8mG/iI4MyHP7S9AdhGd0Vtq3/96qGIeCpwHXBgdRbNzC664f+h6iCTLMDwX+6v6TYB364OMskaNwFzGf7Q7gZgF90Ffw5/9Upm3gC8pTqHZsbhvzHPoLtZUN83AW9hTsMf2i0Al3q1v3rsIuCe6hCaul3AOQ7/Det7CXgL8MvzfGPaYgHYDVxSHULaqMy8g+4RoBqOXcBPZeZfVgeZZIGH/1hfS8Dchz+0eQ3AJzLz6dUhpM2IiKcDH6vOoan4Dt3w/3B1kEl6MPyX69M1AS+l20rPfRa3uAFY6KdnSWv0SeD26hDatO8AZzv8p643m4DMfFPV9WgtFoAd1QGkzcrM3cCN1Tm0KePh/5HqIJP0cPiP9aIEVGqxAOysDiBNya3VAbRh/wCc5fCfuWfQg/sEVLEASP1lAein8fCf9XPjN2UAw3/sx7EErKrFAiBJVcbD/6PVQSYZ0PAfswSsosUCsKU6gDQlx1YH0Lp8m+7KdId/DUvAChYAqb8sAP0xHv7XVAeZZMDDf8wSsEyLBeCU6gDSZkXEEnBydQ6tybeB52XmX1cHmaSB4T9mCRhpsQCcUx1AmoLTgKOrQ2i/xsP/P1cHmaSh4T9mCaDNAnBqRBxTHULapPOqA2i/vgWc6fBfWM2XgBYLwBLw6uoQ0kZFxFF0tw/V4hoP/4W+XXPDw3/sx4EPtloCWiwAAK+MiCdWh5A26ELg8OoQ2qfx8P94dZBJHP57/BiNloBWC8DBwBtH/wFIvRERTwVeXp1D+3QfsNXh3zvjEvC46iDz1GoBgO4c6murQ0hrNbp25XLgwOosWtV4+H+iOsgkDv99+jG6awKaKQEtPg54uQRekpmXVQeRJomIw4CrgVOrs2hV9wHPzcxrq4NM4vBfk4/RfXLjW9VBZq3lDQB0BeidEXGhpwO0qCLiRLrH/zr8F9O9OPyHpJlNQOsFALoS8DrgfV4YqEUSnRcB1wMnVefRqhz+w9RECWj9FMBKu4BLgUsy87bqMGrT6C5/ZwAXA08pjqN9uxc4IzM/VR1kEof/pnyc7hMdgzwdYAFY3W7gWmA7sIPuEcI7M/O+0lQanIg4ADiG7hkVJwBbgbPxLn+L7h66d/4O/+EbbAmwAEjS+txD987/+uogkzj8p2qQJcACIElrdw/wE5n56eogkzj8Z+LjdFuff6gOMi1eBChJa3M3Dv+W/Xe668QGwxuKSNL+jYf/juogkzj8Z+ZPgH+Zmburg0yTGwBJmuwu4DkO/2YNcviDBUCSJrmL7p3/DdVBJnH4z8xghz94CkCS9mX8zv9vqoNM4vCfmUEPf3ADIEmruRN4tsO/WYMf/mABkKSV7qR75/+31UEmcfjPTBPDHywAkrTcN+ne+Tv829TM8AcLgCSNfZPunf/fVQeZxOE/M00Nf/AiQEmCh9/531gdZBKH/8w0N/zBDYAk3QGc7vBvVpPDHywAktp2B907/5uqg0zi8J+ZZoc/WAAktet2unf+Dv82NT38wWsAJLVpPPw/Ux1kEof/zDQ//MENgKT2OPzb5vAfsQBIasnfA89y+DfL4b+MBUBSK/6e7p3/Z6uDTOLwnxmH/woWAEktuI3unb/Dv00O/1V4EaCkoRsP//9aHWQSh//MOPz3wQ2ApCG7FYd/yxz+E7gBkDRU4+H/36qDTOLwnxmH/364AZA0RA7/tjn818ACIGlobgGe6fBvlsN/jSwAkobkFrp3/p+rDjKJw39mHP7rYAGQNBQ76d75O/zb5PBfJy8ClDQE4+H/36uDTOLwnxmH/wa4AZDUd9/A4d8yh/8GuQGQ1Gfj4f+F6iCTOPxnxuG/CRaA1e0GrgW2Azvo1os7M/O+0lQanIg4ADgG2AKcAGwFzgaOrszVE1+nu+DP4d8mh/8mBZDVIRbILuBS4JLMvK06jNoUEUvAGcDFwFOK4yyqr9O98/9idZBJHP4z4/CfAgvAw7YBr8rMm6uDSLBneJxPV0qPLI6zSL5G987f4d8mh/+UWAC6v/6LgNdnZut/L7SAIuJE4ArgpOosC+BrdO/8v1QdZBKH/8w4/Keo9QKQwEsy87LqINIkEXEYcDVwanWWQjfTvfN3+LfJ4T9lrX8M8CKHv/ogM+8FzqM7992im/Gdf8sc/jPQ8gZgG/B81/7qk4h4KnAdbX2C56t07/y/XB1kEof/zDj8Z6TVDcAuugv+HP7qlcy8AXhLdY45+irdO3+Hf5sc/jPUagG41Kv91WMXAfdUh5iDr9AN/68U55jI4T8zDv8Za7EA7AYuqQ4hbVRm3gG8vTrHjH0Fh3/LHP5z0GIBuNab/GgAtlUHmKEv0w3/r1YHmcThPzMO/zlpsQBsrw4gTcEngdurQ8yAw79tDv85arEA7KgOIG3W6AfkjdU5puxLdMN/oa/PcfjPjMN/zlr6KNHYzuoA0pTcWh1gisbD/2vVQSZx+M+Mw79AixsAC4CGYigF4Is4/Fvm8C/S4gZA0uIYD/+FvsOhw39mHP6FWtwAbKkOIE3JsdUBNmkncLrDv1kO/2IWAKm/+lwA7gOe5wV/zXL4L4AWC8Ap1QGkzYqIJeDk6hwb9ADdczgW+lMMDv+ZcfgviBYLwDnVAaQpOA04ujrEBv1iZv6n6hCTOPxnxuG/QFosAKdGxDHVIaRNOq86wAZdmJl/Wh1iEof/zDj8F0yLBWAJeHV1CGmjIuIo4KXVOTbgXZn5+uoQkzj8Z8bhv4ACaPGRuLuAJy/6BUjSaiLiTcArq3Os083AD2Xmwj7F0OE/Mw7/BdXiBgDgYOCNo//gpd6IiKcCL6/OsU4JvMTh3ySH/wJrtQBAdw71tdUhpLUaXbtyOf27gdfvZuY11SH2xeE/Mw7/BdfqKYCx8TuTy6qDSJNExGHA1cCp1VnW6b8AP5yZ360OshqH/8w4/Hug5Q0AdAXonRFxoacDtKgi4kS6x//2bfh/F3ixw785Dv+eaL0AQFcCXge8LyKeWJxF2iM6LwKuB06qzrMBv5uZN1WHWI3Df2Yc/j1iAXjYecDnIuK3vU+AKkXEUkRsBXYA7wKOLI60Ed8E3lAdYoKDgCdXhxgYh3/PtH4NwL7sBq4FttP9EN4J7MzM+0pTaXAi4gDgGLpnVJwAbAXOpr93+Rv73zPzjdUhJomIxwBXAqdXZxkAh38PWQAkTduXgR/IzPurg+yPJWAqHP495SkASdP2b/sw/AEy8x+As4C/qs7SUw7/HnMDIGma/gY4JTN79XMlIg4BrsJNwHo4/HvODYCkabq4b8MfIDO/g5uA9XD4D4AbAEnTchtwfGY+WB1ko0abgCuBZ1dnWWAO/4FwAyBpWv6kz8Mf9mwCzgY+Up1lQTn8B8QNgKRpSOCfZOYXq4NMg5uAVTn8B8YNgKRp+OhQhj+4CViFw3+ALACSpuGt1QGmzRKwh8N/oDwFIGmz7gS2LOpDfzar8dMBDv8BcwMgabMuH+rwh6Y3AQ7/gbMASNqs/1gdYNYaLAEO/wZ4CkDSZtwJHNP3j/+t1eh0wBXAc6qzzJDDvxFuACRtxuWtDH/Yswn4KeDD1VlmxOHfEAuApM0Y/Pp/pQGXAId/YzwFIGmjmlr/rzSw0wEO/wa5AZC0UdtaHf4wqE2Aw79RFgBJG/We6gDVBlACHP4N8xSApI1oev2/Uk9PBzj8G+cGQNJGNL3+X2nZJuA/VWdZI4e/LACSNqT59f9KPSoBDn8BngKQtH6u/yeIiIPpTgf8RHWWVTj8tYcbAEnrVbr+j4iDqo69Fpm5i8XcBDj8tRcLgKT1Klv/R8TxwIcj4nFVGdZiAUuAw1+PYAGQtB53UvtAnJ8Gfgz4oCVgzRz+WpUFQNJ6VF/9/4LRH5+OJWAtHP7aJwuApPUou/f/aP1/6rJf6lsJ+Ms5H9rhr4ksAJLW6pvAXxUe/6fpPrm0XJ9KwDnMrwQ4/LVfFgBJa1X96N8X7OPXLQF7c/hrTSwAktZqkdb/K1kCOg5/rZk3AlrdbuBaYDuwA9gJ7MzM+0pTaXAi4gDgGGALcAKwFTgbOLoy1yq+CRxbtQGIiFcBv7eGb/04cGZmfmvGkTZldLOg7cAZU3xZh7/WxQKwt13ApcAlmXlbdRi1KSKW6AbDxcBTiuOMvS0zX1Z18Ij4BHDaGr+9xRLg8Ne6WQAetg14VWbeXB1EAoiIAM6nK6VHFsd5bmbO+yp2YM/6/2YeeQHgJC2VAIe/NsRrALoC9Drg+Q5/LZLsvBv4EeCzhVEW8er//enbNQEf2uBLOPy1Ya0XgARekpkXZaabEC2kzPwS3fr72qII1Tf/eeEGf1+fSsC5rL8EOPy1Ka0XgIsy87LqENL+ZOa9wHnA1wsOX33v/x/dxEsMtQQ4/LVpLReAbcDrq0NIazW6MPVcYJ7vxvu4/l9paCXA4a+paLUA7KK74M+1v3olM28A3jLHQ/Z1/b/SUEqAw19T02oBuNQL/tRjFwH3zOlYlev/72Fz6/+V+l4CHP6aqhYLwG7gkuoQ0kZl5h3A2+dwqCGs/1fqawlw+GvqWiwA13qTHw3AtnkcY0Hv/b9ZfSsBr8LhrxlosQBsrw4gTcEngdtnfIwhrf9X6k0JyMzfd/hrFlosADuqA0ibNRoIN87wEENc/6/UixIgzUqLBWBndQBpSm6d4WsPdf2/kiVAzbIASP01ywJQ+ejfWa//V7IEqEktFgBJk30T+Gjh8eex/l/JEqDmtFgAtlQHkKbk2Bm9bivr/5UsAWqKBUDqr1kVgJbW/ytZAtSMFgvAKdUBpM2KiCXg5Bm89B20t/5fyRKgJrRYAM6pDiBNwWnA0TN43er1/7Tu/b9ZlgANXosF4NSIOKY6hLRJ583odatv/vO0quOvwhKgQWuxACwBr64OIW1URBwFvHQGL+36/5EsARqsFgsAwCsj4onVIaQNuhA4fAave4Xr/1VZAjRIrRaAg4E3RsSivduQJoqIpwIvn9HLXz2j192vBVz/r2QJ0OC0WgCgO4f62uoQ0lqNrl25HDhwBi//EPDhGbzuWr2AxVv/r2QJ0KC0XAAALoyIn68OIe1PRBxG9wjg42d0iB2ZedeMXnstqm7+s16DLwER8a+rM2g+Wi8AAbwzIi70dIAWVUScSPf431NneJi/nOFrTzS6HmeR1/8rDbYERMQbgddU59B8tF4AoCsBrwPe54WBWiTReRFwPXDSjA9304xff5JFvPp/fwZVAkb/rv1fwK9WZ9H8WAAedh7wuYj4be8ToEoRsRQRW4EdwLuAI+dw2C/M4Rj70pf1/3JJ9/Oz8rbFUzG6q+RbgVdUZ9F8Bd2/yNrbbuBaYDvdD+GdwM7MvK80lQYnIg4AjqF7RsUJwFbgbGZzl79JDs3Mb835mOP1/1foxwYg6X4uvAd4b2Z+vTjPpo3+/XsH8AvLfvm2zJzVcya0QGZxNfEQLAH/6+hrDy8T0EDdWjH8RxZ9/Z/AdXQPSBrE0B+LiAOBy4AXVWdRDQuAH52iMQAAC75JREFUpFsKj72I6//x0B+/0/9acZ6pi4hH0Z1een51FtWxAEi6v+KgEfFY4Ecqjr2KBD7Fw+/0Bzf0xyLiILpy81PVWVTLAiDpgaLj/hC1FyKPh/57gPcMeeiPRcTBwF8AZ1ZnUT0LgKSSDQDwjwqOuXzovzczby7IUCIiDgGuAJ5TnUWLwQIg6aGi485z+C5f7zcz9MdGp1uuAp5ZHEULxAIgqeKdOMDngbuAx8/o9Zev95sb+mMRcSjwQVZ8qkmyAEh6QsVBM/O7EXEZ8K+m+LLX8/A7/a9O8XV7KSKOoHvKY59utaw58UZAkr6bmQdXHDgijgP+BtjMjWeu5+F3+s0P/bGI+Ed0z3h46jp/qzcCaoQFQBLA4Zl5b8WBI+LpwAeAQ9fx2xz6E0TEUXSPdz55A7/dAtAIC4AkgKdl5vVVB4+IJwN/zuSB9WkeXu9/ZR65+mj0LJOPsPEHSFkAGuE1AJKguyFPWQHIzM9FxP8CnAH8LPC9wOOA/w+4Adju0N+/0aOjrwb+SXUWLT4LgCSAH64OkJkJfGj0pXWKiFOA91N0Uaf6x8cBS4LFuSWvNiAizgSuweGvdbAASAJ4ckR43reHIuKldHf4e2x1FvWLBUASdBcEL+KT+bQP0Xkd8DY8nasNsABIGvO58D0REcfTfczvwuos6i8LgKSxUyPiidUhNFlEnA/cBJxenUX9ZgGQNBbAi6tDaHURcXhE/N/Au5nd8xPUEG8EJGm5W4H/ITO/Wx1ED4uIZwPvAOaxofFGQI1wAyBpuWOBC6pDqBMR/1NEvJ/ufL+nZzRVFgBJK/16RBxQHaJlEfG9EfFO4O+A51Xn0TBZACSt9H3Az1WHaFFEHBkRvwN8DvgF/BmtGfIaAEmruR34gcy8szpICyLiVOBlwPnAY4rjeA1AI2yXklZzNPC71SGGLCKOiIhfiYibgE8C/5z64a+GuAGQNMlzMvMj1SGGIiIOBJ5F93HLFwCH1CZalRuARlgAJE3yVeCUzLyjOkhfLRv6LwDOA46qTbRfFoBGWAAk7c81wBmZ+UB1kL4YDf1nAi+kH0N/OQtAIywAktbiDzPzl6tDLLLRRyefRT+H/nIWgEb4BClJa/GKiPhsZr65OsgiGdDQV4MsAJLW6k0R8WBm/lF1kErLhv4LgH+GQ189ZQGQtFYBvCUiHp2Zf1AdZp4c+hoiC4Ck9fr9iDgkM99QHWSWHPoaOi8ClLRR7wZ+MTPvqw4yLaOh/0wePqd/dGmgGl4E2AgLgKTN+Dzwwsy8sTrIRjn0H8EC0AgLgKTN2gX8BvCmzHyoOsxaOPQnsgA0wgIgaVr+C/DKzPzP1UFWs2zoj8/pO/RXZwFohAVA0rT9v8C/zcwvVwdx6G+IBaARFgBJs7AbuBz4vcz8+DwP7NDfNAtAIywAkmZtB/A24IrMvGUWBxgN/WfQndN36G+OBaARFgBJ85LA9XSbgQ8Bn83M+zf6Yg79mbEANMICIKnKg8DngJuAzwC3Ancv+3oAOGzZ1+HL/vwfA2cBT5h76uGzADTCAiBJWs4C0Iil6gCSJGn+LACSJDXIAiBJUoMsAJIkNcgCIElSgywAkiQ1yAIgSVKDLACSJDXIAiBJUoMsAJIkNcgCIElSgywAkiQ1yAIgSVKDLACSJDXIAiBJUoMsAJIkNcgCIElSgw6sDiAVuB24Crga+DLwDeC2zHyoNFUDIuIA4BhgC3ACsBU4Gzi6MpfUogCyOoQ0J38L/CbwoczcXR1GnYhYAs4ALgaeUhxHXRk+tjqEZs9TAGrBXcDPA0/NzA86/BdLZu7OzKuBU4CfAb5ZHElqghsADd3ngbMz8/PVQbQ2EXEicAVwUnWWRrkBaIQbAA3ZDuBpDv9+ycwvAacB11ZnkYbMAqCh2gmck5l3VwfR+mXmvcB5wNers0hDZQHQED1IN/x3VgfRxmXmbcC5dP88JU2ZBUBD9NbM3FEdQpuXmTcAb6nOIQ2RFwFqaL4FfP/o3aMGICKOAr4AHF6dpRFeBNgINwAamj9z+A9LZt4BvL06hzQ0FgANzbbqAJoJ/7lKU+YpAA3JPcDRmflAdRBN1+hugbfiLYPnwVMAjXADoCG50eE/TKO7N95YnUMaEguAhsSP/Q3brdUBpCGxAGhILADDZgGQpsgCIElSgywAGpIt1QE0U16YJk2RBUBDYgEYNguANEUWAA3JyRHxqOoQmr7RxwBPrs4hDYkFQENyOPCM6hCaidPwHgDSVFkANDTnVQfQTPjPVZoy7wSoofFhQAMTEUcCX8SHAc2LdwJshBsADc3jgAurQ2iqfhOHvzR1bgA0RA8Cp2bmjuog2pyI+CHgeuDR1Vka4gagEW4ANEQHAtsjwo8F9lhEPAG4Aoe/NBMWAA3VFroScER1EK1fRBxG9wjg763OIg2VBUBDdgrwqYh4UnUQrV1EnAh8ku6jf5JmxAKgoXsScF1EvDgiojqM9i06L6I7539SdR5p6CwAasHjgcuAGyLizNFd5bQgImIpIrYCO4B3AUcWR5Ka4KcA1KLbgauAq4EvA9+gu/L5odJUDYiIA4Bj6K7ROAHYCpyNd/lbJH4KoBEWAEnSchaARrgKlSSpQRYASZIaZAGQJKlBFgBJkhpkAZAkqUEWAEmSGmQBkCSpQRYASZIaZAGQJKlBFgBJkhpkAZAkqUEWAEmSGmQBkCSpQRYASZIaZAGQJKlBFgBJkhq0BHyrOoQkaWHcWx1A87EE7KwOIUlaGM6ERiwBt1SHkCQtjG9UB9B8uAGQJC3nTGiEBUCStJwbgEZ4CkCStJxvChvhBkCStJwbgEa4AZAkLeebwka4AZAkLedMaMQS8GXg7uogkqRyn83M71aH0HwsZeYDwAeqg0iSyl1eHUDzM34WgP/QJUnbqgNofiIziYhDgduBR1cHkiSV+FpmPrE6hOZnCSAz7wP+qjiLJKmOm+DGLH8csP/wJaldrv8bE5nZ/UnEcXQ3gIjSRJKkebsTOCYzH6wOovnZswHIzFuATxVmkSTVuNLh356lFf/7z0tSSJIqvbs6gOZvzykAgIg4BPg8cHxZIknSPH0sM3+8OoTmb68NQGZ+B7iwKIskaf5eXR1ANfbaAABExBJwE3BSSSJJ0rz8eWa+qDqEajyiAABExFnAlfOPI0mak/uBH8zML1UHUY2VFwECkJlXAX895yySpPl5s8O/batuAAAi4mnAdfONI0mag7uB78vMO6uDqM6qGwCAzPwU8N45ZpEkzcfFDn/tcwMAEBHfA+wAnjC3RJKkWboWeGZm3l8dRLX2uQEAyMyvAefRXSwiSeq3m4HzHP6C/RQAgMz8JPBLc8giSZqdbwFnZ+Zt1UG0GPZbAAAy80+B35ltFEnSjOwGfi4zb6oOosWxpgIw8hrgg7MKIkmamd/IzCuqQ2ixTLwI8BHfHHEY3UcDf3BmiSRJ0/TOzLygOoQWz7oKAEBEfD9dCThyJokkSdPyMeA5XvSn1aznFAAAmfkF4EeB/zr9OJKkKXkX8FyHv/Zl3QUA9ioBXhMgSYtlN/B/ZObPjp7wKq1qQwUAIDPvBc7CTwdI0qK4h+6jfm+oDqLFt+5rAFZ9kYgLgD8CDtr0i0mSNuLzwDmZ+d+qg6gfplIAACLiNGAb3jZYkubtauBnMvPu6iDqjw2fAlhpdMfAU/ABQpI0L3fT3aPlJx3+Wq+pbQD2etHuUcJvAJ4x9ReXJO0C3gT8+8y8qzqM+mkmBWDPi0ecBfwWcNLMDiJJ7XgI+DPgtZn59eow6reZFgCAiFgCLgAuAo6f6cEkabiuAP5NZn62OoiGYeYFYM+BIg6he6rg+cDTgJjLgSWpv+4G3g/8YWZ+ojqMhmVuBWCvg0YcB5wNnAucDjx67iEkaTHdDGwfff11Zj5YnEcDVVIA9goQcSiwla4MPA84ojSQJM3fjcDlwPbM/NvqMGpDeQFYLiIeBZwAHAdsGX0dt+KPW4DHVWWUpHV4CPh7YCdwyz7++LXM/PuyhGrW/w8u9r0WA56DsQAAAABJRU5ErkJggg==" />
                        </defs>
                    </svg>
                </div>
                <div class="text-container">
                    <div class="box-content">
                        <span class="big">Learners Registered</span>
                        <div class="number"> <?php echo number_format($kpi_data_LR['user_id']); ?> </div>
                    </div>
                </div>
            </div>
            <div class="kpi-item">
                <div class="icon Contract1"><svg width="35" height="35" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <circle opacity="0.1" cx="35" cy="35" r="35" fill="#FD8D35" />
                        <mask id="mask0_7_3065" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="18" y="18" width="34" height="34">
                            <rect x="18" y="18" width="34" height="34" fill="url(#pattern0_7_3065)" />
                        </mask>
                        <g mask="url(#mask0_7_3065)">
                            <rect x="-1" y="-2" width="71" height="69" fill="#FD8D35" />
                        </g>
                        <defs>
                            <pattern id="pattern0_7_3065" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_7_3065" transform="scale(0.00195312)" />
                            </pattern>
                            <image id="image0_7_3065" width="512" height="512" xlink:href="data:image/png;base64,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" />
                        </defs>
                    </svg>
                </div>
                <div class="text-container">
                    <div class="box-content">
                        <span class="big">Learners Enrolled</span>
                        <div class="number"><?php echo number_format($kpi_data_Enrolled['user_id']); ?> 
                  </div>
                    </div>
                </div>
            </div>
            <div class="kpi-item">
                <div class="icon Clock11"><svg width="35" height="35" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <circle opacity="0.1" cx="35" cy="35" r="35" fill="#FD8D35" />
                        <mask id="mask0_7_3068" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="18" y="18" width="34" height="34">
                            <rect x="18" y="18" width="34" height="34" fill="url(#pattern0_7_3068)" />
                        </mask>
                        <g mask="url(#mask0_7_3068)">
                            <rect x="-1" y="-2" width="71" height="69" fill="#FD8D35" />
                        </g>
                        <defs>
                            <pattern id="pattern0_7_3068" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_7_3068" transform="scale(0.00195312)" />
                            </pattern>
                            <image id="image0_7_3068" width="512" height="512" xlink:href="data:image/png;base64,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" />
                        </defs>
                    </svg>

                </div>
                <div class="text-container">
                    <div class="box-content">
                        <span class="big">Certificates Issued</span>
                        <div class="number"><?php echo number_format($kpi_data_CI['status']); ?> </div>
                    </div>
                </div>
            </div>
            <div class="kpi-item">
                <div class="icon Checklist1"><svg width="35" height="35" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <circle opacity="0.1" cx="35" cy="35" r="35" fill="#08BDA4" />
                        <mask id="mask0_7_3066" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="18" y="18" width="34" height="34">
                            <rect x="18" y="18" width="34" height="34" fill="url(#pattern0_7_3066)" />
                        </mask>
                        <g mask="url(#mask0_7_3066)">
                            <rect x="-2" y="4" width="108" height="96" fill="#08BDA4" />
                        </g>
                        <defs>
                            <pattern id="pattern0_7_3066" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_7_3066" transform="scale(0.00195312)" />
                            </pattern>
                            <image id="image0_7_3066" width="512" height="512" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAgAAAAIACAYAAAD0eNT6AAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAIABJREFUeJzt3XnUHVWZqPHnywCEBEHCPIkQxiuSYAuCyIygtgIiMgi04BKkldZGuIp6L7SN2o1KizQ2Km2LyCCickUZVCAEUFSmQJiFCIooEEAIAgn5cv/YJysh5BuqTlXtXbue31rv0u71nXPeqlPmfc+uXXsPIKVtC+B/AZsCm/RidWACMAl4FTA2WnaSqjAPeA54qvefc4D7gHuBe4A7gYeiZZepgdgJSEuZArwV2AXYCVgzajaSUvF74Ope/AL4S9RsMmADoBSsCrwHOBzYAa9LScMbBH4FfAc4H5gbN5128h9axfQG4JPAvsC4yLlIaqe5wAXAqcDvIufSKjYAimFX4ERgz9iJSMrGAuB7wBeAWZFzaQUbADVpY+CrwNtjJyIpW4PAOcAngMcj55I0Z0+rCROAzxDu1W0RORdJeRsApgEfBF4AbgIWRs0oUY4AqG6vBy4CNoudiKROugE4BHg4diKpGRM7AWXtcMJMXYu/pFjeDNwO7B87kdR4C0B1WIHweM7/AcZHzkWSVgAOAFYkrCPgLQG8BaDqTQJ+iDP8JaXpR4RbAi/ETiQ2GwBVaU3gcsIEHElK1TWE9UeeiZ1ITDYAqsr6hKG1KbETkaRR+A2wB/Bs7ERicRKgqrA+oaO2+Etqi22B/wcsHzuRWGwA1K9FxX/j2IlIUkG7AhfS0QnxnTxoVWZ9YDoWf0nttTnhKYFfxE6kaTYAKstf/pJysQNwG3Bv7ESa5CRAlWHxl5SbpwhPMD0UO5GmOAdARTnsLylHrwa+S4fqorcAVIS//CXlbAPgEeCW2Ik0wVsAGq1Fv/w36uM9/tB7H0lK1RzCxMAnYidSt84Mdagvi37591v8d60mHUmqzWTg32In0QRHADSSKob9FxX/B3ATDknpW0jYynxW7ETq5AiAhlN18ZekNhgAPhE7ibo5AqCh1FX8HQGQ1AYLgC2A+2MnUhdHALQsVTzq9wdgF/zlL6mdxgLHx06iTo4AaGl1D/s7AiCpLZ4D1gLmxk6kDo4AaEne85ekxSYC+8ZOoi42AFrE4i9Jr3RY7ATq4i0AQXWL/OwCPDjC33kLQFKbLADWA/4cO5GqOQKgKhf5Gan4S1LbjAV2i51EHWwAus1hf0kamauYKivrs3hlvrLxMMVHDvr5PMOIFS8ApwPbESaGqVoTCef2q4RzHfv7Xjoc3VQ2YhV/+vxMw4gRfwS2Rk2ZSjjnsb/3pWODOg9aakLM4k+fn2sYTccLWPxjmEp6IwFvq/WII3AOQLc0OdtfysHXgZmxk+ig24Bvxk5iKZvFTqBqNgDdYfGXijsvdgIdltq5twFQK1n8pXLuip1Ah90ZO4GlbBo7garZAOTP5/wltVFqC9VNjp1A1WwA8uauflJ/toydQIeldu5fFTuBqtkA5Mthf6l/h8ZOoMPeFzuBpawUOwFpNGI/6jec2I/yGEaReIHwSJqaNRV4kfjf/9LXgpS0lIs/feZlGDHij9gENCnVhYAW1nnQUr9SL/70mZthxIoXgTOANwGTUNUmAdsTznFqv/yXjKykNstS5bVlY5/s/kckqTOyqplOAsxDW4q/JCkRNgDtZ/GXJBVmA9BuFn9JUik2AO1l8ZcklWYD0E4Wf0lSX2wA2sfiL0nqmw1Au1j8JUmVsAFoD4u/JKkyNgDtYPGXJFXKBiB9Fn9JUuVsANJm8Zck1cIGIF0Wf0lSbWwA0mTxlyTVygYgPRZ/SVLtbADSYvGXJDXCBiAdFn9JUmNsANJg8ZckNcoGID6LvySpcTYAcVn8JUlR2ADEsz4wnf6L/y5Y/KXcvQh8FXgTMAkYyCwm9Y7tjN6xStlan1C0F/YRDwMbNZ14Bfo5ZsPoYvwR2JrumEo45tjnfVmRlYHYCXTQol/+/RTvRb/8H6wgn6Zl9z8iqUYvAtsBM2Mn0rCpwI3A8rETWUpWNdNbAM3qevGXVMzX6V7xB7gN+GbsJHJnA9Aci7+kos6LnUBEXT72RmQ1nJEwZ/sv5i0AafRWAubGTiKSScCzsZNYSlY10xGA+ln8Jak461PNPMH18lE/Sf3YMnYCEXX52BthA1Af7/lL6tehsROI6H2xE8hdVvczEmLxH5pzAKTRe5GwQM5tsRNp2FTg18BysRNZSlY10xGA6ln8JVVleeAnhILYFVMJx5xa8ZeG1eUV/kYr9kpehtHGeJGwTO6ipYBzMwnYnsVLAcc+30NFVrIazojM2f6jk93/iCR1RlY101sA1bD4S5JaxQagfxZ/SVLr2AD0x+IvSWolG4DyLP6SpNayASjH4i9JajUbgOIs/pKk1rMBKMbiL0nKgg3A6Fn8JUnZsAEYHYu/JCkrNgAjs/hXZ/nYCUiSgnGxE0icG/tUa+XYCUiSAkcAhrbol3+/xX9XLP6LbBA7AUlS4AjAsvnLvx5bxE5AkhQ4AvBK/vKvz1tiJyBJCrLa2rAC/vKvzwAwG3hN7EQkqaSsaqYjAIv5y79eO2Hxl6Rk2AAEPupXv4/ETkCStFhWwxklOexfv62A27DhlNRuWdXMrv+DvBZwNRb/Og0AZ+C1JklJ6fI/yisDlwNT+ngPi//IPgLsHDsJSdLLZTWcUcAKhOK/Sx/vYfEf2Q6EERaXAJaUg6xqZldHAL6Fxb9urwMuxeIvSUnqYgNwNHBwH6+3+I9sB+BaYNXYiUiSli2r4YxReD1wIzCh5Ost/sMbINzz/yL+8peUn6xqZlYHM4IJwK3AZiVfb/Ef3laE2f5O+JOUq6xqZpc2A/okFv+qDRDW9z8WeDfdvKUkSa2UVTczjI2BWYTZ/0VZ/IPlgZWADQm7+r0FeCsu7yupO7KqmVkdzDB+ArwjdhKSpFbLqmZmdTBD2A24KnYSkqTWy6pmduGe7YmxE5AkKTVZdTPLMBW4hfyPU5JUv6xqSe4jAJ8msy9MkqQq5FwcVwceAcbHTkSSlIWsambOIwAHY/GXJGmZcm4A3hc7AUmSUpXVcMYSpgD3x05CkpSVrGpmriMAe8VOQJKklOXaALghjSRJw8hqOKNnAHgUWDN2IpKkrGRVM3McAdgci78kScPKsQF4XewEJElKXY4NwKaxE5AkKXU5NgCbxE5AkqTU2QBIktRBOTYAk2MnIElS6nJsACbFTkCSpNTl2ABMjJ2AJEmpswGQJKmDslrVqGdh7AQkSVnKqmbmOAIgSZJGYAMgSVIH2QBIktRBNgCSJHWQDYAkSR1kAyBJUgfZAEiS1EE2AJIkdZANgCRJHWQDIElSB9kASJLUQTYAkiR1kA2AJEkdZAMgSVIH2QBIktRBNgCSJHWQDYAkSR1kAyBJUgeNi52AOm8+cB9wF/BH4GHgUeAR4M/AIPBU728X/ecEYAVgLLAaMLkXawOv7cVGwJbAik0chCS1zUDsBGqwMHYCGtIgMBOYDtwMzALuBubV9HljgU2ArYFtgR2BbbDxlVROVjUzq4PpsQFIywPApcA1wHUs/hUfy0RgB+BtwDuBKXHTkdQiWdXMrA6mxwYgvjuBHwA/JPziT9lmwHuAQwi3DCRpKFnVzKwOpscGII6/AucC3wDuiJxLWVOBw4H3A6+Om4qkBGVVM7M6mB4bgGb9FjgLuBD4W+RcqrIicDDwYWBa5FwkpSOrmpnVwfTYADRjOnAKcFXkPOo0QLg98A1glci5SIovq5qZ1cH02ADU6wpC4b8hdiIN2hq4HpgUOxFJUWVVM10ISKM1C9iTMHu+S8UfwkTGz8ROQpKqlFU30+MIQLWeAE4iDIO/FDmXmFYgnIuJsRORFE1WNdMRAA3nHGBT4Gt0u/gDvAD8JnYSklQVV0TTsjwCHAVcFjuRxMyJnYAkVcUGQEs7B/gY8HSDn7kCYUGeTZeItYDVWbzO/9JD788RCvIc4HHCvgH3LRXPV5znOhW/nyRFk9X9jB7nAJTzN+AfCQ1A3dYjrMu/PWFZ3qlU34y+BNxGmLB4A2EW/6N9vN+qhCZjfP+pSWqprGpmVgfTYwNQ3P2E591vr/EzpgH7AO8i3uI6NwOX9GJWwdeeCpxQeUaS2iSrmpnVwfTYABRzKXAYYSnfqq0HHElYWve1Nbx/P+4njHZ8i5FHBvYDvk/YXVBSd+VYM7Oy0Bh1nEn1RW2AsMveTwjD8LGPcaSYT9i0aO9lnIuVgc+15DgMw6g/spJjN5Pdl1SDhcCngH+r8D3HAO/tve9WFb5vkx4FbiRMgFyfMEfB5/4lLZJVzczqYHpsAIb3EnAE8N2K3m8AOIiwWNBmFb2nJKUoq5rpY4DdsgD4B+D8it5vG+ArwFsqej9JUkNsALqjyuK/KmFW/BG4mmQ/XiA8nvgzwqOKjwFPEkZpNuzFm4ADgClRMpSkFok9SSTFWFT8q/A2wkqBsY+pzfEsYf7F6gXO+xsIT2zEzt0wuhxKXOwLJMX4577OaDAJOAsYTOB42hyXAKsVPPdL2pPwCGPs4zCMLoYSF/sCSS3O7O90AmFy390JHEub4yXgRKqZRDQZuC6BYzKMrkVWsprR2JPdl9SHywkr7/Wzk9/fE54YWLmSjLppHnAwYb2BqiwPXAjsW+F7ShpeVjUzq4PpsQEIZhHW2X+2j/f4NPBZnOjXj78B+wNX1PDeE4DpwLY1vLekV8qqZmZ1MD02AGGnvG2Bu0q+fgD4KvCRyjLqpmcIqyLOqPEzphA2PXLBIql+WdVMf9nl6VjKF/+xwNlY/Pv1FLAX9RZ/gN8RmjVJKiSrbqan6yMA5wGHlnzteOACwpC1ynsUeCvFdxwsazVgNuFJDUn1yapmOgKQl9nAMSVfOwD8Dxb/fj0E7ERzxR/gCcJ3J0mjZgOQlw9RftLfl4D3VZhLF80GdiUMyzft8gifKanFbADycS5hSdkyPgkcV2EuXXQ7YffA2ZE+fwbwYqTPltRCWd3P6OniHIDHgS0JQ8FF7Ut4Pj3Ha6EpvyEskfxk5DxmAq+PnIOUs6z+nXQEIA8nUK74bwJ8m8wu6oZNB/YgfvGHNHKQ1BI2AO03kzD8X9RE4Ae4wl8/LgPeTn+LLVXJBkDSqNkAtN8nCBv0FHUGsFXFuXTJxcB+wPOxE1nCirETkNQeNgDtNh24ssTr3g4cUW0qnXIeYW3/ebETWcqGsROQ1B453vvtyiTAhYTlfm8q+LqVCc+or1d5Rt1wBvBR0rvOxhJuRUyInYiUsaxqpiMA7fVzihd/gNOw+Jf1eeCfSK/4A7wFi7+kAmwA2uvUEq/ZFof+yzqZsDtiqlzBUVIhWQ1n9KT466xqtwLblHjddcCOFeeSu4WEzZXOjJ3IMFYEHgDWip2IlLmsaua42AmolC+WeM3+WPyLWgAcBXwrdiIjOB6Lv6SCsupmenIfAXiMcA9/foHXjCNsD7xJLRkN7ynC7niPAS8Qcl8bmBwhlyLmEfZGuDh2IiNYG7gPdwKUmpBVzXQEoH0uoFjxBziAZov/TOD7wE96/31ZNiIsQ7wf6Y1MPE8YMUl9g52xwHew+EsSEEYAco6pJc7JLQ3ldg+h2SjaJW9PmJ8Q+9wuBOYCuxfMP4YB4Cziny/D6FIocbEvkDpjqF/Tw9mrodxOpb8RpQHgw4TRjVjndw7hSYnUjQG+Tvzr0TC6Fkpc7Aukzji+xPm4suacngcOLZHXUHYnrGnf9Ll9lHYsjTyOsBJh7GvRMLoYSlzsC6TO2LTguVifMJO9rnwGgfcWzGk0XkeYNNjUeX2IOBMki1oeuIT416FhdDWy4kJA7fEAYbZ3EYdT73d8CnBRDe87C9gJ+HMN77202cCuwP0NfFY/VgR+DOwTOxFJSlXsDrGuOK3EubinxnxupP4GcivqHQm4nXY8P78KcAPxr0HD6HpkxRGA9vhpwb9/I7BZHYn0nEi5bYiLuIMwJ+DxGt77t8AuNDPK0I/VgauAHWInIikvNgDt8CJwfcHXvKOORHquAq6p8f2XdAewB/BEhe85o/eeT1b4nnVYF7iWcss+S9KwbADa4RZCE1BEnQ3Ad2t872W5nTASUEUTcDmwN/BMBe9Vp9cA04EtIuchKVM2AO1wY8G/X4P6fjUOEmeFvCqagB8D7yY8upiyzQkjPlNiJyJJbRJ7kkgdcWDBc3BYjbncWjCXqm1NaAKK5n0OYenc1E2j2UcgU4xrCCtKroM/UnI2hvAdv5dwqyv2dTeaUOJiXyB1xIYFz8EZNeZyacFc6lC0CfhP2rGJxw6EzZNiX2+xYh7wwb7PotrqaMI1EPs6HC6UuNgXSNUxl+LF68Ya8/lmwVzqMpXRNQFfiJVgQXsQvuvY11vMsPjrQ8S/DocLJS72BVJ1FB1yX46w7W5d+XylYD51GqkJ+FS81Ap5F/V+Z22Ia/o+i8rFDOJfj0OFEhf7Aqk6Lix4/NMSy6duy2oCBoFjYyZVwMGkP+zZRBzQ74lUNg4i/vU4VGTFCTbpK7r8b91r2q9b8/sXdRuwJ4uf6V8AfIAwDyJ1RxEeqRwfO5EEFF3nQvmaETsBtVfsDrHqOKLg8Z9Ycz5z6G/b37psQ9jRry2/JI8jjFTEvr5SiTY8oaFmjCX+9ThUZMURgPQVXQZ3o1qyWGxVYMeaP6OMW4CNge/HTmQUTga+TDueTGjKgtgJKBleCw2xAUhf0YVvXltLFi+3bwOfUcbfYicwggFC4T8pdiKSZAOQvqIjAKvVksXLHUlYbVCjNwCcThj6l6TobADSN6fg369USxav/IwTG/icXIwHzqM9TyZI6oAc70HmNlFjOWB+gb9/AphcUy5LehHYDpjZwGe12fLARYRn/TW0HP8tUnmp/jue1XWa1cH0pHrhlDWOYpNi5tPcLP2HgW2BvzT0eW0zEbiEsMqfhpfjv0UqL9V/x7O6Tr0FkL6iM2Kb/E43IBS4iQ1+ZlusAvwMi7+kRNkApK3M4zBNP0LzJuAKYFLDn5uyVYErCZv7SFKSbADSVmYYrMh8garsSNgl0JGAsFLi9YRbI5KULBuAtI0jTCIrItYiGrvgSMCGwHRgi7hpSNLIbADSt3LBv3+mlixGZ0fgcrrZBGwOXAdMiZ2IJI2GDUD6ijYAf64li9HrYhMwjbCByXqxE5Gk0bIBSF/bGgBY3AQ0sShRbG8EfgGsHjsRSSrCBiB9RQtLCg0AdGNi4B7ANYRZ/5LUKjYA6XtNwb//Qy1ZlLMz8FPybAL2AX5CnscWg9sBaxGvhYbYAKSv6O5+d9aSRXk5NgGHABdT/AkNDW3N2AkoGWvFTqArbADSV3QEILUGAEITcAV5zAk4CjiX5pZb7oodYyegZOwUOwG118LM4tcFj388YaOe2HkvK6bT7pGAjwODxD+POcb00X8Nytx1xL8ehwolLvYFUnU8S/GRmlsTyHu4f+jb2AScTDPnZwHhlsnxwJHACYQnKl5q6PNjxtGj/TKUrWOIfx0OF0pc7Aukjti84Dk4PYGch4vptKcJGABOo5nzcicwdYg8pgJ3NZRHrJgHfGiI41f+jiFcA7Gvw+FCiYt9gdQRhxQ8B/slkPNIMZ30m4AxwDdo5nzcB0weIZ/JwO8ayidmzAAOJOyr4IzwfI0lfMcHkfaw/5KhxMW+QOqILxc8B5MJQ8mx8x4pppNuEzAeOJ/mzsVoJz7t0mBOhmG8PLLiUwDt8MaCfz8HuL2ORCq2M2k+IrgC8APg4IY+72bCr97RmE47vltJibMBaIftKL62/g/rSKQGqTUBEwkL/Lyzwc+8qODf31BLFpI6xQagHZYjFMoiLq4jkZqk0gSsAvwM2L3hzy36Xf21liwkdYoNQHvsUfDv7wZm1ZFITWI3AWsAVwM7NPy5twAPFnyNuw5K6psNQHvsWeI136s8i3rtDFxG803AusC1hG19m1b0OxpLuWtBkl5mIHYCNchupuYSNiM8LjZaawMPEWa0t8kNwNsIiyDVbUPg58CUBj5rafOA9YHHCrxmF8IOhJKal1XNdASgXQ4s+PePAj+qI5GavZmw+l3dewdsTnj+OEbxhzD5r0jxB9i/jkQkdY8NQLsUXRAI4MzKs2hG3U3ANMKjdzHvp3+t4N8PELYhlqS+2QC0y+bAVgVfMwOYWUMuTXgz4ZG8V9XwvtcAq1f8vkXcBPyq4Gt2ItwykKS+2QC0z6ElXvOvlWfRnJ0IhXLjit7vCMI9/5Urer+y/qXEa46qPAtJykjspSLrjseBCQXPyQBhtbnYufcTcwj3v8tOwlkJODuB41gI3Fgi/9WAFxLI3TC6HFlxBKB9ViNsnlHEQuD/1pBLk1YlLJjzS2A3Rt8IrAR8GpgNfKCe1Ao7qcRr3g8sX3Eekjosq0caerLr0pbhFuANJV53PeH+dw7+AFxKGM5/CPgT8BxhQZ81CPsn7EV4bC72CoNLupriKw2OAe4BNqk+HUkFZFUzszqYni40AAA7UnxN+K0Jk8/GVZ+ORmEeMJWwSmMRB1B8vwBJ1cuqZnoLoL0+U+I1M4GvVp2IRu00ihf/AeCTNeQiqeOy6mZ6ujICAGE4/5cFXzMJuAsfJ2vaw8CWhNsURewDXFJ9OpJKyKpmOgLQbp8t8Zq5wNF0q1GKbZDw+GHR4j9AmMAoSZWzAWi33QnPyRd1OfCVinPR0E4lTP4r6gDCZEZJqlxWwxk9XftlezOwHbCg4OvGE54K2LbyjLSkmwlbDM8r+LoJhFs1G1adkKTSsqqZjgC03xuAD5Z43XzgMOCZatPREuYQNnAqWvwBPo7FX1KNsupmero2AgDwFGGr4MdLvHYvwnr7PhpYrfnA3pQb+l8XuJe01i+QlFnNdAQgD68GPl/ytVcCx1SYi4JjKVf8ITyqafGXpIJirxUdKwYpvsLckv4jgWPIJU4teO6XdGgC+RuGsezISlbDGT3ZfUkFPEJY7W9OidcOEPan/1ClGXXP2YRd+8pch+sAdxD2PZCUnqxqprcA8rIucGbJ1y4EPgx8u7JsuueblC/+A73XW/wlNcIGID8HEmb3lzFI2DHvO9Wl0xlnE0ZPyo5AHQe8vbp0JGl4WQ1n9HT5FsAicwnPnt9R8vUDhC1rT6oso7z9O3Ai5a+9HYDphLUZJKUrq5qZ1cH02AAEvyesIvdEH+/xAeC/sDANZQHwEeCsPt5jTcL2zutUkpGkOmVVM70FkK8NgfPp7/n+/wb2Bf5aRUKZmQO8g/6K//LAxVj8JUVgA5C3PYEv9vkelwHbAL/tP51s/AqYRlhDoawxhLkWO1aSkSQp+nOiKUYV+8kvR1grYDCB44kVg8CXqOaWyFcSOB7DMIqFEhf7AkkxBglbAFdhL+DBBI6p6biXcjsvLssJCRyPYRjFQ4mLfYGkGguAg/s4r0uaAJxM2OQm9nHVHfOB06luad6jCd9F7OMyDKN4KHGxL5CUYx5wUPlT+wrTCOvdxz6uuuIyYKvKzhZ8jG7fQjGMtocSF/sCST0GgY+WPrvLtgdwUwLHVlX8Ftit0jMExydwXIZh9BdKXOwLpC3xqbIneAgDwHsIM+RjH1vZmAHsQ7XP+g4ApyRwbIZh9B9KXOwLpE1xOv2tEzCUNwHfI9w/j32MI8V84CJguxrOw4TeeYh9jIZhVBNZyWpVo57svqSazQAOAB6r4b0nA/sDhxOWu03peruL8Bz+t4G/1PD+qwE/wuf8pZyk9G9Y37I6mB4bgOIeJKz4V3bvgNHYjNBovBXYnnpGHoYzD7iBsHjPJYTH+uqyaARkgxo/Q1LzsqqZWR1Mjw1AOXMJ2wE3sRPgSoRJdrsSVhmc2vv/Velp4FbgZuA6wtMKcyv+jKUNEJ7xPwX3T5BylFXNzOpgemwA+nMxYVvbOQ1+5gAwBdgaeA2wPuHX8zrA6sBY4FWEojoGeJGwP8F84EngT8BDhA2QHgZuZ/FiRU1ZAzgH2LvBz5TUrKxqZlYH02MD0L8/AUfS31r3XXIQYWnfNWMnIqlWWdVMNwPSsqwDXE64HWBRG9o6wA+BC/A8SWoZGwANZQA4DLgHOJYwDK9gOeA4wkTC/SLnIkmlZDWc0eMtgHrcRthVsMu3BQaAA4HPARtFzkVS87KqmY4AaLSmAlcQZtTvGjmXGHYDfk0Y7rf4S2q9rLqZHkcAmnEN8HngKvI952OAdxPW8a9jpUBJ7ZJVzczqYHpyLUapugs4AzgXeC5yLlVZiTD/4Thg48i5SEpHVjUzq4PpsQGI42nCUwPnE4bK22YA2Bk4grB88cS46UhKUFY1M6uD6bEBiO8Bwr3yC4E7I+cynDHA3wHvBA7Be/uShpdVzczqYHpsANLye+BnvbiKMFIQ06rALsA7euHz+5JGK6uamdXB9NgApGsBYX3+mwhr9N8MzCIs6VuH5QibEG1H2I1w+97/neN1L6l+Wf3bkdXB9NgAtMs84HfAbML6/bMJowZPE9b7fxp4itAkLL3BzkTCtrtr9P5zMmHS3mbApoR9BVzASFJVsqqZWR1Mjw2AJKkOWdVMFwKSJKmDbAAkSeogGwBJkjrIBkCSpA6yAZAkqYNsACRJ6iAbAEmSOsgGQJKkDrIBkCSpg2wAJEnqIBsASZI6yAZAkqQOsgGQJKmDbAAkSeogGwBJkjrIBkCSpA6yAZAkqYNsACRJ6iAbAEmSOsgGQJKkDrIBkCSpg2wAJEnqIBsASZI6yAZAkqQOsgGQJKmDbAAkSeogGwBJkjrIBkCSpA6yAZAkqYNsACRJ6iAbAEmSOmhc7ATUefOB+4C7gD8CDwOPAo8AfwYGgad6f7voPycAKwBjgdWAyb1YG3htLzYCtgRWbOIgJKltBmInUIOFsRPQkAaBmcB04GZgFnA3MK+mzxsLbAJsDWwL7Ahsg42vpHKyqplZHUyPDUBaHgAuBa4BrmPxr/hYJgI7AG8D3glMiZuOpBbJqmZmdTA9NgDx3Qn8APgh4Rd/yjYD3gMcQrhlIElDyapmZnUwPTYAcfwVOBf4BnBH5FzKmgocDrwfeHXcVCQlKKuamdVLUAZjAAAX9UlEQVTB9NgANOu3wFnAhcDfIudSlRWBg4EPA9Mi5yIpHVnVzKwOpscGoBnTgVOAqyLnUacBwu2BbwCrRM5FUnxZ1cysDqbHBqBeVxAK/w2xE2nQ1sD1wKTYiUiKKqua6UJAGq1ZwJ6E2fNdKv4QJjJ+JnYSklSlrLqZHkcAqvUEcBJhGPylyLnEtALhXEyMnYikaLKqmY4AaDjnAJsCX6PbxR/gBeA3sZOQpKq4IpqW5RHgKOCy2IkkZk7sBCSpKjYAWto5wMeApxv8zBUIC/JsukSsBazO4nX+lx56f45QkOcAjxP2DbhvqXi+4jzXqfj9JCmarO5n9DgHoJy/Af9IaADqth5hXf7tCcvyTqX6ZvQl4DbChMUbCLP4H+3j/VYlNBnj+09NUktlVTOzOpgeG4Di7ic87357jZ8xDdgHeBfxFte5GbikF7MKvvZU4ITKM5LUJlnVzKwOpscGoJhLgcMIS/lWbT3gSMLSuq+t4f37cT9htONbjDwysB/wfcLugpK6K8eamZWFxqjjTKovagOEXfZ+QhiGj32MI8V8wqZFey/jXKwMfK4lx2EYRv2RlRy7mey+pBosBD4F/FuF7zkGeG/vfbeq8H2b9ChwI2EC5PqEOQo+9y9pkaxqZlYH02MDMLyXgCOA71b0fgPAQYTFgjar6D0lKUVZ1UwfA+yWBcA/AOdX9H7bAF8B3lLR+0mSGmID0B1VFv9VCbPij8DVJPvxPDAD+DnwS8Jjhk8RvqsNCBMntyc8obFppBwlqTViTxJJMRYV/yq8jbBSYOxjanM8A3yBsNDRaE0jTKyMnbthdDmUuNgXSIrxz32d0WAScBYwmMDxtDnOI6xsWNYewL0JHIdhdDGUuNgXSGpxZn+nEwiT++5O4FjaHC8RVlqswqtwNMAwYkRWsprR2JPdl9SHywkr7/Wzk9/fE54YWLmSjLppHnAwYb2BqowHLgTeXeF7ShpeVjUzq4PpsQEIZhHW2X+2j/f4NPBZnOjXj78B+wNX1PDeE4DpwLY1vLekV8qqZmZ1MD02AGGnvG2Bu0q+fgD4KvCRyjLqpmcIqyLOqPEzphA2PXLBIql+WdVMf9nl6VjKF/+xwNlY/Pv1FLAX9RZ/gN8RmjVJKiSrbqan6yMA5wGHlnzteOACwpC1ynsUeCvFdxwsazVgNuFJDUn1yapmOgKQl9nAMSVfOwD8Dxb/fj0E7ERzxR/gCcJ3J0mjZgOQlw9RftLfl4D3VZhLF80GdiUMyzft8gifKanFbADycS7ws5Kv/SRwXIW5dNHthGV7Z0f6/BnAi5E+W1ILZXU/o6eLcwAeB7YkDAUXtS/h+fQcr4Wm/IawRPKTkfOYCbw+cg5SzrL6d9IRgDycQLnivwnwbTK7qBs2nbA8b+ziD2nkIKklbADabyZh+L+oicAPcIW/flwGvJ3+Fluqkg2ApFGzAWi/TxA26CnqDGCrinPpkouB/Qhb+qZixdgJSGoPG4B2mw5cWeJ1bweOqDaVTjmPsLb/vNiJLGXD2AlIao8c7/12ZRLgQsJyvzcVfN3KhGfU16s8o244A/go6V1nYwm3IibETkTKWFY10xGA9vo5xYs/wGlY/Mv6PPBPpFf8Ad6CxV9SATYA7XVqiddsi0P/ZZ1M2B0xVa7gKKmQrIYzelL8dVa1W4FtSrzuOmDHinPJ3ULC5kpnxk5kGCsCDwBrxU5EylxWNXNc7ARUyhdLvGZ/LP5FLQCOAr4VO5ERHI/FX1JBWXUzPbmPADxGuIc/v8BrxhG2B96kloyG9xRhd7zHgBcIua8NTI6QSxHzCHsjXBw7kRGsDdyHOwFKTciqZjoC0D4XUKz4AxxAs8V/JvB94Ce9/74sGxGWId6P9EYmnieMmKS+wc5Y4DtY/CUJCCMAOcfUEufkloZyu4fQbBTtkrcnzE+IfW4XAnOB3QvmH8MAcBbxz5dhdCmUuNgXSJ0x1K/p4ezVUG6n0t+I0gDwYcLoRqzzO4fwpETqxgBfJ/71aBhdCyUu9gVSZxxf4nxcWXNOzwOHlshrKLsT1rRv+tw+SjuWRh5HWIkw9rVoGF0MJS72BVJnbFrwXKxPmMleVz6DwHsL5jQaryNMGmzqvD5EnAmSRS0PXEL869AwuhpZcSGg9niAMNu7iMOp9zs+BbiohvedBewE/LmG917abGBX4P4GPqsfKwI/BvaJnYgkpSp2h1hXnFbiXNxTYz43Un8DuRX1jgTcTjuen18FuIH416BhdD2y4ghAe/y04N+/EdisjkR6TqTcNsRF3EGYE/B4De/9W2AXmhll6MfqwFXADrETkZQXG4B2eBG4vuBr3lFHIj1XAdfU+P5LugPYA3iiwvec0XvPJyt8zzqsC1xLuWWfJWlYNgDtcAuhCSiizgbguzW+97LcThgJqKIJuBzYG3imgveq02uA6cAWkfOQlCkbgHa4seDfr0F9vxoHibNCXhVNwI+BdxMeXUzZ5oQRnymxE5GkNok9SaSOOLDgOTisxlxuLZhL1bYmNAFF8z6HsHRu6qbR7COQKcZ0woqSa+OPlJyNAdYhPEp8LfGvu9GEEhf7AqkjNix4Ds6oMZdLC+ZSh6JNwH/Sjk08diBsnhT7eosV8wi7L6qbjiZcA7Gvw+FCiYt9gVQdcylevG6sMZ9vFsylLlMZXRPwhVgJFrQH4buOfb3FDIu/PkT863C4UOJiXyBVR9Eh9+UI2+7Wlc9XCuZTp5GagE/FS62Qd1Hvd9aGmN7vSVQ2ZhD/ehwqlLjYF0jVcWHB45+WWD51W1YTMAgcGzOpAg4m/WHPJqKOJaXVTgcR/3ocKrLiBJv0FV3+t+417det+f2Lug3Yk8XP9C8APkCYB5G6owiPVI6PnUgCroudgJIxI3YCaq/YHWLVcUTB4z+x5nzm0N+2v3XZhrCj3wGxExml4wgjFbGvr1SiDU9oqBljiX89DhVZcQQgfUWXwd2oliwWWxXYsebPKOMWYGPg+7ETGYWTgS/TjicTmrIgdgJKhtdCQ2wA0ld04ZvX1pLFy+3bwGeU8bfYCYxggFD4T4qdiCTZAKSv6AjAarVk8XJHElYb1OgNAKcThv4lKTobgPTNKfj3K9WSxSs/48QGPicX44HzaM+TCZI6IMd7kLlN1FgOmF/g758AJteUy5JeBLYDZjbwWW22PHAR4Vl/DS3Hf4tUXqr/jmd1nWZ1MD2pXjhljaPYpJj5NDdL/2FgW+AvDX1e20wELiGs8qfh5fhvkcpL9d/xrK5TbwGkr+iM2Ca/0w0IBW5ig5/ZFqsAP8PiLylRNgBpK/M4TNOP0LwJuAKY1PDnpmxV4ErC5j6SlCQbgLSVGQYrMl+gKjsSdgl0JCCslHg94daIJCXLBiBt4wiTyIqItYjGLjgSsCFhU5st4qYhSSOzAUjfygX//plashidHYHL6WYTsDlhPfspsRORpNGwAUhf0Qbgz7VkMXpdbAKmETYwWS92IpI0WjYA6WtbAwCLm4AmFiWK7Y3AL4DVYyciSUXYAKSvaGFJoQGAbkwM3AO4hjDrX5JaxQYgfa8p+Pd/qCWLcnYGfkqeTcA+wE/I89hicDtgLeK10BAbgPQV3d3vzlqyKC/HJuAQ4GKKP6Ghobm5lBZZK3YCXWEDkL6iIwCpNQAQmoAryGNOwFHAuTS33HJXvCV2AkrGTrETUHstzCx+XfD4xxM26omd97JiOu0eCfg4MEj885hjXFvge1DeriP+9ThUKHGxL5Cq41mKj9TcmkDeQ8V02tkEnEwz52cB4ZbJ8cCRwAmEJypeaujzY8bRo/0ylK1jiH8dDhdKXOwLpI7YvOA5OD2BnIeL6bSnCRgATqOZ83InMHWIPKYCdzWUR6yYB3xoiONX/o4hXAOxr8PhQomLfYHUEYcUPAf7JZDzSDGd9JuAMcA3aOZ83AdMHiGfycDvGsonZswADiTsq+CM8HyNJXzHB5H2sP+SocTFvkDqiC8XPAeTCUPJsfMeKaaTbhMwHjif5s7FaCc+7dJgToZhvDyy4lMA7fDGgn8/B7i9jkQqtjNpPiK4AvAD4OCGPu9mwq/e0ZhOO75bSYmzAWiH7Si+tv4P60ikBqk1ARMJC/y8s8HPvKjg399QSxaSOsUGoB2WIxTKIi6uI5GapNIErAL8DNi94c8t+l39tZYsJHWKDUB77FHw7+8GZtWRSE1iNwFrAFcDOzT8ubcADxZ8jbsOSuqbDUB77FniNd+rPIt67QxcRvNNwLqEhWimNfy5UPw7Gku5a0GSXmYgdgI1yG6m5hI2IzwuNlprAw8RZrS3yQ3A2wiLINVtQ+DnwJQGPmtp84D1gccKvGYXwg6EkpqXVc10BKBdDiz4948CP6ojkZq9mbD6Xd17B2xOeP44RvGHMPmvSPEH2L+ORCR1jw1AuxRdEAjgzMqzaEbdTcA0wqN3Me+nf63g3w8QtiGWpL7ZALTL5sBWBV8zA5hZQy5NeDPhkbxX1fC+1wCrV/y+RdwE/Krga3Yi3DKQpL7ZALTPoSVe86+VZ9GcnQiFcuOK3u8Iwj3/lSt6v7L+pcRrjqo8C0nKSOylIuuOx4EJBc/JAGG1udi59xNzCPe/y07CWQk4O4HjWAjcWCL/1YAXEsjdMLocWXEEoH1WI2yeUcRC4P/WkEuTViUsmPNLYDdG3wisBHwamA18oJ7UCjupxGveDyxfcR6SOiyrRxp6suvSluEW4A0lXnc94f53Dv4AXEoYzn8I+BPwHGFBnzUI+yfsRXhsLvYKg0u6muIrDY4B7gE2qT4dSQVkVTOzOpieLjQAADtSfE34rQmTz8ZVn45GYR4wlbBKYxEHUHy/AEnVy6pmegugvT5T4jUzga9WnYhG7TSKF/8B4JM15CKp47LqZnq6MgIAYTj/lwVfMwm4Cx8na9rDwJaE2xRF7ANcUn06kkrIqmY6AtBuny3xmrnA0XSrUYptkPD4YdHiP0CYwChJlbMBaLfdCc/JF3U58JWKc9HQTiVM/ivqAMJkRkmqXFbDGT1d+2V7M7AdsKDg68YTngrYtvKMtKSbCVsMzyv4ugmEWzUbVp2QpNKyqpmOALTfG4APlnjdfOAw4Jlq09ES5hA2cCpa/AE+jsVfUo2y6mZ6ujYCAPAUYavgx0u8di/Cevs+Glit+cDelBv6Xxe4l7TWL5CUWc10BCAPrwY+X/K1VwLHVJiLgmMpV/whPKpp8ZekgmKvFR0rBim+wtyS/iOBY8glTi147pd0aAL5G4ax7MhKVsMZPdl9SQU8Qljtb06J1w4Q9qf/UKUZdc/ZhF37ylyH6wB3EPY9kJSerGqmtwDysi5wZsnXLgQ+DHy7smy655uUL/4Dvddb/CU1wgYgPwcSZveXMUjYMe871aXTGWcTRk/KjkAdB7y9unQkaXhZDWf0dPkWwCJzCc+e31Hy9QOELWtPqiyjvP07cCLlr70dgOmEtRkkpSurmpnVwfTYAAS/J6wi90Qf7/EB4L+wMA1lAfAR4Kw+3mNNwvbO61SSkaQ6ZVUzvQWQrw2B8+nv+f7/BvYF/lpFQpmZA7yD/or/8sDFWPwlRWADkLc9gS/2+R6XAdsAv+0/nWz8CphGWEOhrDGEuRY7VpKRJCn6c6IpRhX7yS9HWCtgMIHjiRWDwJeo5pbIVxI4HsMwioUSF/sCSTEGCVsAV2Ev4MEEjqnpuJdyOy8uywkJHI9hGMVDiYt9gaQaC4CD+zivS5oAnEzY5Cb2cdUd84HTqW5p3qMJ30Xs4zIMo3gocbEvkJRjHnBQ+VP7CtMI693HPq664jJgq8rOFnyMbt9CMYy2hxIX+wJJPQaBj5Y+u8u2B3BTAsdWVfwW2K3SMwTHJ3BchmH0F0pc7AukLfGpsid4CAPAewgz5GMfW9mYAexDtc/6DgCnJHBshmH0H0pc7AukTXE6/a0TMJQ3Ad8j3D+PfYwjxXzgImC7Gs7DhN55iH2MhmFUE1nJalWjnuy+pJrNAA4AHqvhvScD+wOHE5a7Tel6u4vwHP63gb/U8P6rAT/C5/ylnKT0b1jfsjqYHhuA4h4krPhXdu+A0diM0Gi8FdieekYehjMPuIGweM8lhMf66rJoBGSDGj9DUvOyqplZHUyPDUA5cwnbATexE+BKhEl2uxJWGZza+/9V6WngVuBm4DrC0wpzK/6MpQ0QnvE/BfdPkHKUVc3M6mB6bAD6czFhW9s5DX7mADAF2Bp4DbA+4dfzOsDqwFjgVYSiOgZ4kbA/wXzgSeBPwEOEDZAeBm5n8WJFTVkDOAfYu8HPlNSsrGpmVgfTYwPQvz8BR9LfWvddchBhad81YyciqVZZ1Uw3A9KyrANcTrgdYFEb2jrAD4EL8DxJahkbAA1lADgMuAc4ljAMr2A54DjCRML9IuciSaVkNZzR4y2AetxG2FWwy7cFBoADgc8BG0XORVLzsqqZjgBotKYCVxBm1O8aOZcYdgN+TRjut/hLar2supkeRwCacQ3weeAq8j3nY4B3E9bxr2OlQEntklXNzOpgenItRqm6CzgDOBd4LnIuVVmJMP/hOGDjyLlISkdWNTOrg+mxAYjjacJTA+cThsrbZgDYGTiCsHzxxLjpSEpQVjUzq4PpsQGI7wHCvfILgTsj5zKcMcDfAe8EDsF7+5KGl1XNzOpgemwA0vJ74Ge9uIowUhDTqsAuwDt64fP7kkYrq5qZ1cH02ACkawFhff6bCGv03wzMIizpW4flCJsQbUfYjXD73v+d43UvqX5Z/duR1cH02AC0yzzgd8Bswvr9swmjBk8T1vt/GniK0CQsvcHORMK2u2v0/nMyYdLeZsCmhH0FXMBIUlWyqplZHUyPDYAkqQ5Z1UwXApIkqYNsACRJ6iAbAEmSOsgGQJKkDrIBkCSpg2wAJEnqIBsASZI6yAZAkqQOsgGQJKmDbAAkSeogGwBJkjrIBkCSpA6yAZAkqYNsACRJ6iAbAEmSOsgGQJKkDrIBkCSpg2wAJEnqIBsASZI6yAZAkqQOsgGQJKmDcmwA5sVOQJKUnRdjJ1C1HBuAubETkCRl59nYCVQtxwYguy9JkhRddrXFBkCSpJE9EzuBquXYADwROwFJUnbmxE6gajk2APfFTkCSlJ17YydQtRwbgOy+JElSdNnVFhsASZJGll1tybEBmBU7AUlSdu6MnUDVcmwAHgJ+HzsJSVI2HgD+EDuJquXYAABcHTsBSVI2sqwpuTYA18ROQJKUjSxrykDsBGqyFvBHYGzsRCRJrbYAWAd4LHYiVct1BODPZDpkI0lq1M/JsPhDvg0AwLmxE5AktV62tSTXWwAAEwkjAZNiJyJJaqW5hFvKz8VOpA45jwA8B1wQOwlJUmudR6bFH/IeAQDYGLgHGBc7EUlSqywAtgDuj51IXXIeAYCweMNFsZOQJLXOd8m4+EP+IwAQOrhZ5N/sSJKqMQi8ngyX/11SF4ri3cA5sZOQJLXG2WRe/KEbIwAAkwlzAVaLnYgkKWlzgM2BJ2InUreurJT3PPAs8PexE5EkJe1Y4IbYSTShKyMAEG53zADeHDsRSVKSrgV2BRbGTqQJXWoAANYDbsVbAZKkl3sSmAY8HDuRpnRhEuCS/gj8Ax3p7iRJo7IQOIIOFX/ozhyAJd0PrIi3AiRJwReA/4qdRNO62AAAXAVsBGwdOxFJUlQXAh+hgyPDXZsDsKTxwKXAXrETkSRFcTXwduDF2InE0OUGAGAl4BfAtrETkSQ16tfAHoQd/zqpa5MAl/YssBtwZexEJEmNuRp4Kx0u/mADAGGrx3cR7gNJkvL2A8Kw/zOxE4mtq5MAl7YA+BEwAdgBb41IUm4WEmb7/yPwUuRckmChe6U9CNtArhk7EUlSJZ4grAFzWexEUmIDsGzrA+cDO8ZORJLUl2uB9wGPxE4kNc4BWLY/ADsROsbHI+ciSSruKeBjhIneFv9lcA7A8GYC/0PYO2BrHDGRpNQNAt8E9gGm08EFfkbLgjZ6GwP/GzgSGBc5F0nSyw0SZvifBNwdOZdWsAEobmPgE8DBwKTIuUhS1z1LmLN1KvBg5FxaxQagvBWAdwKHA3vjqIAkNWUQ+BXwHULx7/SCPmXZAFRjTWB3wmST3YDXxk1HkrLzIGEFv6sJG7o9Fjed9rMBqMcGwJbA5sBmvViVsPfAKoRbB8tFy06S0jKP8Cv+acKQ/hzgPuBe4B7gTsLTWarQ/wd2ciDPSMkwqQAAAABJRU5ErkJggg==" />
                        </defs>
                    </svg> </div>
                <div class="text-container">
                    <div class="box-content">
                        <span class="big">Learners Active</span>
                        <div class="number"><?php echo number_format($kpi_data_AL['active_count']); ?></div>
                    </div>
                </div>
            </div>
            <div class="kpi-item">
                <div class="icon Warning1"><svg width="35" height="35" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <circle opacity="0.1" cx="35" cy="35" r="35" fill="#2E9DEC" />
                        <mask id="mask0_7_3069" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="18" y="18" width="34" height="34">
                            <rect x="18" y="18" width="34" height="34" fill="url(#pattern0_7_3069)" />
                        </mask>
                        <g mask="url(#mask0_7_3069)">
                            <rect x="-19" y="-13" width="108" height="96" fill="#2E9DEC" />
                        </g>
                        <defs>
                            <pattern id="pattern0_7_3069" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_7_3069" transform="scale(0.00195312)" />
                            </pattern>
                            <image id="image0_7_3069" width="512" height="512" xlink:href="data:image/png;base64,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" />
                        </defs>
                    </svg>
                </div>
                <div class="text-container">
                    <div class="box-content">
                        <span class="big">Avg Time</span>
                        <div class="number"> <?php echo number_format($KPI_data_avg['average_usage_hours']); ?>
</div>
                    </div>
                </div>


            </div>
        </div>
        <div class="dashboard-container">
        <div class="dashboard-item" id="OverallLearnersCompletionStatus" style="width: 800px;height:400px;"></div>

            
            <div class="dashboard-item  " id="Learners Completion Status by Domain" style="width: 800px;height:400px; ">
            </div>

        </div>
        <div>
            <div class="dashboard-container">
               
            <div class="dashboard-item  " id="HR Enrolled V/s Completion" style="width: 700px;height:350px;"> </div>
            <div class="dashboard-item  " id="MoE Enrolled V/s Completion" style="width: 700px;height:350px; ">
            </div>


        </div>

        <div class="dashboard-container">

        <div class="dashboard-item  " id="Others Enrolled V/s Completion" style="width: 700px;height:350px; ">
            </div>

            <div class="dashboard-item  " id="Defense Enrolled V/s Completion" style="width: 700px;height:350px; ">
            </div>
                </div>

    </div>
    </div>

<script>
    // PHP variables passed to JavaScript
    var totalCompleted = <?php echo $total_completed; ?>;
    var totalIncompleted = <?php echo $total_incompleted; ?>;
    var totalCourses = totalCompleted + totalIncompleted; // Total number of courses

    // Initialize ECharts instance
    var overallCoursesCompletionChart = echarts.init(document.getElementById('OverallLearnersCompletionStatus'));

    // Chart options
    var overallCoursesCompletionChart_Options = {
        title: {
            text: 'Overall Learners\' Completion Status',
            top: '5%'
        },
        tooltip: {
            trigger: 'item'
        },
        series: [
            {
                name: 'Completion Status',
                type: 'pie',
                radius: ['55%', '80%'],
                center: ['50%', '60%'],
                startAngle: 180,
                endAngle: 360,
                data: [
                    { 
                        value: 0, // Add a zero-value segment to place the total label
                        name: 'Total',
                        itemStyle: { color: 'transparent' },
                        label: {
                            show: true,
                            position: 'outside',
                            formatter: '0', 
                            fontSize: 12,
                            color: '#000',
                            padding: [0, -20, 0, -25] 
                        }
                    },
                    { 
                        value: totalCompleted, 
                        name: 'Completed', 
                        itemStyle: { color: '#6069f3' },
                        label: {
                            show: true,
                            position: 'inside',
                            formatter: totalCompleted.toLocaleString(), 
                            fontSize: 12,
                            color: 'white'
                        }
                    },
                    { 
                        value: totalIncompleted, 
                        name: 'Incompleted', 
                        itemStyle: { color: '#fb8d35' },
                        label: {
                            show: true,
                            position: 'inside',
                            formatter: totalIncompleted.toLocaleString(), 
                            fontSize: 12,
                            color: 'white'
                        }
                    },
                    { 
                        value: 0, // Add a zero-value segment to place the total label
                        name: 'Total',
                        itemStyle: { color: 'transparent' },
                        label: {
                            show: true,
                            position: 'outside',
                            formatter: totalCourses.toLocaleString(), 
                            fontSize: 12,
                            color: '#000',
                            padding: [0, 0, 0, -25] 
                        }
                    }
                ],
                labelLine: {
                    show: false
                },
            },
                {
            name: 'Completion Percentage',
            type: 'gauge',
            center: ['50%', '50%'], 
            radius: '60%',
            startAngle: 180,
            endAngle: 0,
            splitLine: { // Remove the split lines
                show: false
            },
            axisTick: { // Hide the axis ticks
                show: false
            },
            axisLabel: { // Hide the axis labels
                show: false
            },
            pointer: {
                show: false // Hide the pointer
            },
            detail: {
                formatter: '{value}%', // Display percentage in the middle
                fontSize: 24,
                fontWeight: 'bold',
                offsetCenter: [0, '30%'] // Position percentage in the middle
            },
            data: [
                { 
                    value: (totalCompleted / (totalCompleted + totalIncompleted) * 100).toFixed(2) // Calculate the percentage for 'Completed'
                }
            ]
            }
        ]
    };

    // Set options to the chart instance
    overallCoursesCompletionChart.setOption(overallCoursesCompletionChart_Options);


var learnersCompletionStatusbyDomainChart = echarts.init(document.getElementById('Learners Completion Status by Domain'));

  // Data fetched from PHP
  var learnersCompletionData = <?php echo $learnersCompletionData; ?>;

  var learnersCompletionStatusbyDomainChart_Options  = {
    title: {
      text: 'Learners Completions Status by Domain' 
    },
    tooltip: {
      trigger: 'axis',
      axisPointer: {
        type: 'shadow'
      }
    },
    legend: {},
    grid: {
      left: '3%',
      right: '4%',
      bottom: '3%',
      containLabel: true
    },
    yAxis: {
      type: 'category',
      data: ['MoE', 'HR', 'Defense', 'Others'],
      inverse: true,
      axisLabel: {
        fontWeight: 'bold',
        fontSize: 16
      },
      axisLine: {
        show: false
      }
    },
    xAxis: {
      type: 'value',
      boundaryGap: [0],
    },
    series: [
      {
        type: 'bar',
        data: learnersCompletionData, // Use fetched data here
        itemStyle: {
          color: '#6069f3',
          borderRadius: [0, 8, 8 , 0]
        },
        barWidth: '40%',
        barCategoryGap: '20%',
        label: {
          show: true,
          position: 'inside',
          formatter: '{c}',
          fontSize: 12,
          color: 'white'
        }
      },
    ]
  };

  learnersCompletionStatusbyDomainChart.setOption(learnersCompletionStatusbyDomainChart_Options);







// HR Enrolled V/S Completion Status
  var HREnrolledVSCompletion = echarts.init(document.getElementById('HR Enrolled V/s Completion'));

  // Data fetched from PHP
  var HRCompleted = <?php echo $HRCompleted; ?>;
  var HREnrolled = <?php echo $HREnrolled; ?>;

  var HREnrolledVSCompletion_Options = {
    title: {
      text: 'HR Enrolled V/S Completed'
    },
    tooltip: {
      trigger: 'axis'
    },
    legend: {
      right: '10%',
      data: [
        { name: 'Enrolled', icon: 'circle', textStyle: { color: 'black' } },
        { name: 'Completed', icon: 'circle', textStyle: { color: 'black' } }
      ]
    },
    toolbox: {
      show: true,
      feature: {
        dataView: { show: true, readOnly: false },
        magicType: { show: false, type: ['line', 'bar'] },
        restore: { show: false },
        saveAsImage: { show: true }
      }
    },
    calculable: true,
    xAxis: [
      {
        type: 'category',
        data: [
          'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ]
      }
    ],
    yAxis: [
      {
        type: 'value'
      }
    ],
    series: [
      {
        name: 'Enrolled',
        type: 'bar',
        barWidth: 10, // Adjust the width as needed
        data: HREnrolled,
        itemStyle: {
          color: '#6069f3',
          borderRadius: [8, 8, 0 , 0]
        }
      },
      {
        name: 'Completed',
        type: 'bar',
        barWidth: 10, // Adjust the width as needed
        data: HRCompleted,
        itemStyle: {
          color: '#fb8d35',
          borderRadius: [8, 8, 0 , 0]
        }
      }
    ]
  };

  HREnrolledVSCompletion.setOption(HREnrolledVSCompletion_Options);








// MoE Enrolled V/S Completion Status
var MoEEnrolledVSCompletion = echarts.init(document.getElementById('MoE Enrolled V/s Completion'));
 
// Data fetched from PHP
  var MoECompleted = <?php echo $MoECompleted; ?>;
  var MoEEnrolled = <?php echo $MoeEnrolled; ?>;

var MoEEnrolledVSCompletion_Options = {
 title: {
   text: 'MoE Enrolled V/S Completed'
 },
 tooltip: {
   trigger: 'axis'
 },
 legend: {
   right: '10%',
   data: [
     { name: 'Enrolled', icon: 'circle', textStyle: { color: 'black' } },
     { name: 'Completed', icon: 'circle', textStyle: { color: 'black' } }
   ]
 },
 toolbox: {
   show: true,
   feature: {
     dataView: { show: true, readOnly: false },
     magicType: { show: false, type: ['line', 'bar'] },
     restore: { show: false },
     saveAsImage: { show: true }
   }
 },
 calculable: true,
 xAxis: [
   {
     type: 'category',
     data: [
       'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
     ]
   }
 ],
 yAxis: [
   {
     type: 'value'
   }
 ],
 series: [
   {
     name: 'Enrolled',
     type: 'bar',
     barWidth: 10, // Adjust the width as needed
     data: MoEEnrolled,
     itemStyle: {
       color: '#6069f3',
       borderRadius: [8, 8, 0 , 0]
     }
   },
   {
     name: 'Completed',
     type: 'bar',
     barWidth: 10, // Adjust the width as needed
     data: MoECompleted,
     itemStyle: {
       color: '#fb8d35',
       borderRadius: [8, 8, 0 , 0]
     }
   }
 ]
};

MoEEnrolledVSCompletion.setOption(MoEEnrolledVSCompletion_Options);


//  Enrolled V/S Completion Status
var defenseEnrolledVSCompletion = echarts.init(document.getElementById('Defense Enrolled V/s Completion'));

  // Data fetched from PHP
  var defenseCompleted = <?php echo $DefenseCompleted; ?>;
  var defenseEnrolled = <?php echo $DefenseEnrolled; ?>;


 var defenseEnrolledVSCompletion_Options = {
  title: {
    text: 'Defense Enrolled V/S Completed'
  },
  tooltip: {
    trigger: 'axis'
  },
  legend: {
    right: '10%',
    data: [
      { name: 'Enrolled', icon: 'circle', textStyle: { color: 'black' } },
      { name: 'Completed', icon: 'circle', textStyle: { color: 'black' } }
    ]
  },
  toolbox: {
    show: true,
    feature: {
      dataView: { show: true, readOnly: false },
      magicType: { show: false, type: ['line', 'bar'] },
      restore: { show: false },
      saveAsImage: { show: true }
    }
  },
  calculable: true,
  xAxis: [
    {
      type: 'category',
      data: [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
      ]
    }
  ],
  yAxis: [
    {
      type: 'value'
    }
  ],
  series: [
    {
      name: 'Enrolled',
      type: 'bar',
      barWidth: 10, // Adjust the width as needed
      data: defenseEnrolled,
      itemStyle: {
        color: '#6069f3',
        borderRadius: [8, 8, 0 , 0]
      }
    },
    {
      name: 'Completed',
      type: 'bar',
      barWidth: 10, // Adjust the width as needed
      data: defenseCompleted,
      itemStyle: {
        color: '#fb8d35',
        borderRadius: [8, 8, 0 , 0]
      }
    }
  ]
};

defenseEnrolledVSCompletion.setOption(defenseEnrolledVSCompletion_Options);



//  Others V/S Completion Status
var othersEnrolledVSCompletion = echarts.init(document.getElementById('Others Enrolled V/s Completion'));


  // Data fetched from PHP
  var OthersCompleted = <?php echo $OthersCompleted; ?>;
  var OthersEnrolled = <?php echo $OthersEnrolled; ?>;



 var othersEnrolledVSCompletion_Options = {
  title: {
    text: 'Others Enrolled V/S Completed'
  },
  tooltip: {
    trigger: 'axis'
  },
  legend: {
    right: '10%',
    data: [
      { name: 'Enrolled', icon: 'circle', textStyle: { color: 'black' } },
      { name: 'Completed', icon: 'circle', textStyle: { color: 'black' } }
    ]
  },
  toolbox: {
    show: true,
    feature: {
      dataView: { show: true, readOnly: false },
      magicType: { show: false, type: ['line', 'bar'] },
      restore: { show: false },
      saveAsImage: { show: true }
    }
  },
  calculable: true,
  xAxis: [
    {
      type: 'category',
      data: [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
      ]
    }
  ],
  yAxis: [
    {
      type: 'value'
    }
  ],
  series: [
    {
      name: 'Enrolled',
      type: 'bar',
      barWidth: 10, // Adjust the width as needed
      data: OthersEnrolled,
      itemStyle: {
        color: '#6069f3',
        borderRadius: [8, 8, 0 , 0]
      }
    },
    {
      name: 'Completed',
      type: 'bar',
      barWidth: 10, // Adjust the width as needed
      data: OthersCompleted,
      itemStyle: {
        color: '#fb8d35',
        borderRadius: [8, 8, 0 , 0]
      }
    }
  ]
};

othersEnrolledVSCompletion.setOption(othersEnrolledVSCompletion_Options);




</script>


</body>
</html>







