<?php

/* 
 * Variable $user must be an instance of User
 */

                  echo '<tr><td>' . $user->getUserid()
                  . '</td><td>' . $user->displayRole() . '</td>';
                  echo '</tr>';
