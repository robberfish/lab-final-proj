<?php
    require("db.php");
    $item = $_POST['itemID'];
    $getid = "select userID from users where username=".$_SESSION['username'];
    $userid = $con->query($getid);

    $Ysql = "select name, description, cost from items where itemID=$item"; //other users item
    $Yresult = $con->query($sql); 

    $Asql = "select name, description, cost from items where owner=$userid"; //active users item
    $Aresult = $con->query($sql); 

    $transaction_cost = 0.15; //15% cost of shipping or something 

    //stuff thats needed for inserting into cost table
    $getcost = "select cost from items where itemID = $item";
    $yresult = $con->query($getcost);
    $ycost = $yrestult - $yrestult*$transaction_cost;

    $Yid = "select owner from items where itemID=$item";
    $yidResult = $con->query($Yid);
?>
<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>Name </th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = mysqli_fetch_assoc($Yresult)) : ?>
                    <tr><!--info from the db shown here-->
                        <td><?php echo htmlspecialchars($row['name']);?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['cost']);?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>    
        <p>Select an offer: </p>
        <table>
            <thead>
                <tr>
                    <th>Name </th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = mysqli_fetch_assoc($Aresult)) : ?>
                    <tr><!--info from the db shown here-->
                        <td><?php echo htmlspecialchars($row['name']);?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['cost']);?></td>
                        <td>Estimated cost: 
                            <?php 
                                //id1 is the current user
                                //id2 is the owner of the item being viewed
                                //acost is the amount it costs current user
                                //ycost is amount it will cost the item owner\
                                
                                


                                $estcost= $row['cost'] - $row['cost']*$transaction_cost;
                                echo "$estcost";
                                //then basically we compare this number to the cost of $item
                            ?>
                        </td>
                        <form>
                            <input type="hidden" name="id1" value="<?php $userid ?>"> <!--fix this-->
                            <input type="hidden" name="id2" value="<?php $yidResult ?>">
                            <input type="hidden" name="AtransferCost" value="<?php $estcost ?>">
                            <input type="hidden" name="YtransferCost" value="<?php $ycost ?>">
                            <input type="submit" value="Select">
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>  




        show owned items
        calculate what price wouldbe 
    </body>
</html>
<!--
select name, description, cost from items where itemID=$item                              //show item
select name, description, cost from items where owner=(                                   //show items owned by user
    select userID from users where username=$_SESSION['username']
)

select cost from items where itemID=$item
select cost from items where itemID

insert into cost (id1, id2, AtransferCost, YtransferCost) values ()
//atransfercost = cost of id2 * 0.15
//YtransferCost = cost of id1 * 0.15(transaction cost)
                    -->