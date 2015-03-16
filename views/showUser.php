<?php

/* 
 * Variable $user must be an instance of User
 */
                  $myid = $user->getUserid();
                  echo "<tr><td><a href='userInfo.php?user=$myid'>$myid</a></td><td>" 
                          . $user->displayRole() . '</td>';
                  echo '</tr>';
