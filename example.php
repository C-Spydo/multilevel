<?php

include('mlmternary.class.php');

$mbinary = new mlmBinary();

$recordID = empty($_GET['id']) ? null : $_GET['id'];

$currentRep = ( $recordID ) ? $mbinary->rRep($recordID) : null;

if( !empty($_REQUEST['formPosted']) ){
    
    switch( $_REQUEST['action'] ){
        case 'getRep':
            if( !empty($_REQUEST['repID']) ){
                
                $currentRep = $mbinary->rRep($_REQUEST['repID'],false);
                $recordID = ( empty($currentRep) ) ? 0 : $currentRep->recordID;
                
            }elseif( !empty($_REQUEST['recordID']) ){
                
                $currentRep = $mbinary->rRep($_REQUEST['recordID']);
                $recordID = ( empty($currentRep) ) ? 0 : $currentRep->recordID;
                
            }
            break;
        case 'updateRep':
            $mbinary->uRep($_REQUEST['recordID'],$_REQUEST['name'],$_REQUEST['repID'],$_REQUEST['sponsorID'],$_REQUEST['leg']);
            $currentRep = $mbinary->rRep($_REQUEST['recordID']);
            $recordID = $_REQUEST['recordID'];
            break;
        case 'addRep':
            $mbinary->cRep($_REQUEST['name'],$_REQUEST['repID'],$_REQUEST['sponsorID'],$_REQUEST['leg']);
            $currentRep = $mbinary->rRep($_REQUEST['sponsorID']);
            $recordID = $_REQUEST['sponsorID'];
            break;
        case 'addPrim':
            $recordID = $mbinary->cRep($_REQUEST['name'],$_REQUEST['repID'],$_REQUEST['sponsorID'],$_REQUEST['leg']);
            $currentRep = $mbinary->rRep($recordID);
            break;
        case 'addSpill':
            $recordID = $mbinary->cRepSpill($_REQUEST['name'],$_REQUEST['repID'],$_REQUEST['sponsorID']);
            $currentRep = $mbinary->rRep($recordID);
            break;
        case 'swapRep':
            $mbinary->swapReps($_REQUEST['recordID']);
            $currentRep = $mbinary->rRep($_REQUEST['recordID']);
            $recordID = $_REQUEST['recordID'];
            break;
        case 'delRep':
            if( !empty($_REQUEST['repID']) ){
                
                $recordID = $mbinary->dRep($_REQUEST['recordID'],$_REQUEST['repID'],false);
                
            }else{
                
                $recordID = $mbinary->dRep($_REQUEST['recordID'],$_REQUEST['sponsorID']);
                
            }
            $currentRep = $mbinary->rRep($recordID);
            break;
    }
    
}

?>
<!DOCTYPE hmtl>
<html>
    <head>
        <title>MLM Binary Plan</title>
        <style type="text/css">
            a{
                color: black;
                text-decoration: none;
            }
            a:hover{
                color: red;
            }
            div{
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <h3>Multi Level Plaftorm Demo (Ternary: 3 Children)</h3>
        <div id="breadcrumb"><?php echo $mbinary->showBreadcrumb($recordID);?></div>
        <h4>Get Rep record</h4>
        <div>
            <form method="POST">
                Rep ID: <input type="text" name="repID"> record ID: <input type="text" name="recordID"> 
                <input type="hidden" name="formPosted" value="1">
                <input type="hidden" name="action" value="getRep">
                <input type="submit" value="Go">
            </form>
        </div>
<?php
if( !empty($currentRep) ){
?>
        <table width="100%" border="1">
            <tr>
                <td colspan="3" align="center"><strong><font color="red"><h2><?php echo $currentRep->name;?></h2></font></strong></td>
            </tr>
            <tr>
                <td width="33%" align="center">
<?php
    if( !empty($currentRep->reps) ){
        
        if( $currentRep->reps[0]->leg ){
            
            $leftLeg = ( empty($currentRep->reps[2]) ) ? null : $currentRep->reps[1];
            $rightLeg = $currentRep->reps[1];
			$middleLeg = $currentRep->reps[0];
		}
        elseif ($currentRep->reps[1]->leg){
            $leftLeg = $currentRep->reps[0];
			$rightLeg = $currentRep->reps[2];//( empty($currentRep->reps[0]) ) ? null : $currentRep->reps[1];
			$middleLeg = $currentRep->reps[1];
		} 
        else{
            
            $leftLeg = $currentRep->reps[1];
			$rightLeg = $currentRep->reps[0];
            $middleLeg = ( empty($currentRep->reps[2]) ) ? null : $currentRep->reps[1];
            
        }
        
    }
    if( empty($leftLeg) ){
?>
                    <div style="text-align: left;">
                        <form method="POST">
                            Name: <input type="text" name="name" value=""><br>
                            Rep ID: <input type="text" name="repID" value=""><br>
                            <input type="hidden" name="formPosted" value="1">
                            <input type="hidden" name="action" value="addRep">
                            <input type="hidden" name="sponsorID" value="<?php echo $currentRep->recordID;?>">
                            <input type="hidden" name="leg" value="0">
                            <input type="submit" value="Add">
                        </form>
                    </div>
<?php
    }else{
?>
                    [<a href="?id=<?php echo $leftLeg->recordID;?>"><?php echo $leftLeg->name;?></a>]
<?php
    }
?>
                </td>
                <td width="33%" align="center">
<?php
    if( empty($middleLeg) ){
?>
                    <div style="text-align: left;">
                        <form method="POST">
                            Name: <input type="text" name="name" value=""><br>
                            Rep ID: <input type="text" name="repID" value=""><br>
                            <input type="hidden" name="formPosted" value="1">
                            <input type="hidden" name="action" value="addRep">
                            <input type="hidden" name="sponsorID" value="<?php echo $currentRep->recordID;?>">
                            <input type="hidden" name="leg" value="1">
                            <input type="submit" value="Add">
                        </form>
                    </div>
<?php
    }else{
?>
                    [<a href="?id=<?php echo $middleLeg->recordID;?>"><?php echo $middleLeg->name;?></a>]
<?php
// Started making middle modifications here
    }
?>

				</td>
                <td width="33%" align="right">
<?php
    if( empty($rightLeg) ){
?>
                    <div style="text-align: left;">
                        <form method="POST">
                            Name: <input type="text" name="name" value=""><br>
                            Rep ID: <input type="text" name="repID" value=""><br>
                            <input type="hidden" name="formPosted" value="1">
                            <input type="hidden" name="action" value="addRep">
                            <input type="hidden" name="sponsorID" value="<?php echo $currentRep->recordID;?>">
                            <input type="hidden" name="leg" value="2">
                            <input type="submit" value="Add">
                        </form>
                    </div>
<?php
    }else{
?>
                    [<a href="?id=<?php echo $rightLeg->recordID;?>"><?php echo $rightLeg->name;?></a>]
<?php
    }
?>
                </td>
            </tr>
<?php
    if( !empty($currentRep->reps) ){
?>
            <tr>
                <td colspan="3" align="center">
                    <form method="POST" style="margin: 0;">
                        <input type="hidden" name="formPosted" value="1">
                        <input type="hidden" name="action" value="swapRep">
                        <input type="hidden" name="recordID" value="<?php echo $currentRep->recordID;?>">
                        <input type="submit" value="Swap Reps">
                    </form>
                </td>
            </tr>
<?php
    }
?>
        </table>
        <h4>Update Record (<?php echo $currentRep->recordID;?>)</h4>
        <form method="POST">
            Name: <input type="text" name="name" value="<?php echo $currentRep->name;?>"><br>
            Rep ID: <input type="text" name="repID" value="<?php echo $currentRep->repID;?>"><br>
            <input type="hidden" name="formPosted" value="1">
            <input type="hidden" name="action" value="updateRep">
            <input type="hidden" name="recordID" value="<?php echo $currentRep->recordID;?>">
            <input type="hidden" name="sponsorID" value="<?php echo $currentRep->sponsorID;?>">
            <input type="hidden" name="leg" value="<?php echo $currentRep->leg;?>">
            <input type="submit" value="Update">
        </form>
        <h4>Delete Record (<?php echo $currentRep->recordID;?>)</h4>
        <form method="POST">
            Assign downline to Rep ID: <input type="text" name="repID"> Record ID: <input type="text" name="sponsorID"><br>
            <input type="checkbox" name="action" value="delRep"> check box to confirm<br>
            <input type="hidden" name="formPosted" value="1">
            <input type="hidden" name="recordID" value="<?php echo $currentRep->recordID;?>">
            <input type="submit" value="Delete">
        </form>
<?php
    if( !empty($leftLeg) AND !empty($rightLeg) AND !empty($middleLeg) ){
?>
        <h4>Add Rep as spill over</h4>
        <form method="POST">
            Name: <input type="text" name="name" value=""><br>
            Rep ID: <input type="text" name="repID" value=""><br>
            <input type="hidden" name="formPosted" value="1">
            <input type="hidden" name="action" value="addSpill">
            <input type="hidden" name="sponsorID" value="<?php echo $currentRep->sponsorID;?>">
            <input type="submit" value="Add">
        </form>
<?php
    }
}else{
?>
        <h4>Primary Reps</h4>
<?php
    $primReps = $mbinary->primReps();
    foreach( $primReps as $primRep ){
?>
        <a href="?id=<?php echo $primRep['recordID'];?>"><?php echo $primRep['name'];?></a> (<?php echo $primRep['recordID'];?>)<br>
<?php
    }
?>
        <h4>Add Rep as primary</h4>
        <form method="POST">
            Name: <input type="text" name="name" value=""><br>
            Rep ID: <input type="text" name="repID" value=""><br>
            <input type="hidden" name="formPosted" value="1">
            <input type="hidden" name="action" value="addPrim">
            <input type="hidden" name="sponsorID" value="0">
            <input type="hidden" name="leg" value="0">
            <input type="submit" value="Add">
        </form>
<?php
}
?>
    </body>
</html>