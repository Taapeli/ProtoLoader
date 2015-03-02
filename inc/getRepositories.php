<?php

/**
 * Create list of existing repositories
 * 
 * @param Everyman\Neo4j\Client $sukudb
 * @return array [0] = source id, [1] = repository name
 */
function getRepositories($sukudb) {

  $return_array = [];

  $query_string = "MATCH (n:Repo) RETURN n ORDER BY n.name";
  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result_repo = $query->getResultSet();

  foreach ($result_repo as $rows_repo) {
    // For every repository
    $repo_id = $rows_repo[0]->getProperty('id');
    $repo_name = $rows_repo[0]->getProperty('name');

    // Obtain repository information
    $return_row = array($repo_id, $repo_name);
    $return_array[] = $return_row;
  } // repo
  return $return_array;
}

/**
 * Create list of existing sources in repositories
 * 
 * @param Everyman\Neo4j\Client $sukudb
 * @return array [0] = source id, [1] = repository + source name
 */
function getSources($sukudb) {

  $return_array = [];

  $query_string = "MATCH (n:Repo) RETURN n ORDER BY n.name";
  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result_repo = $query->getResultSet();

  foreach ($result_repo as $rows_repo) {
    // For every repository
    $repo_id = $rows_repo[0]->getProperty('id');
    $repo_name = $rows_repo[0]->getProperty('name');

    // Obtain sources in each repository
    $query_string = "MATCH (n:Repo)-[:REPO_SOURCE]->(s) WHERE n.name='" .
            $repo_name . "' RETURN s ORDER BY s.title";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result_source = $query->getResultSet();

    foreach ($result_source as $rows_source) {
      // For every source in the repository
      $source_id = $rows_source[0]->getProperty('id');
      $source_name = $repo_name . ': ' . $rows_source[0]->getProperty('title');

      $return_row = array($source_id, $source_name);
      $return_array[] = $return_row;
    } // source
  } // repo
  return $return_array;
}
