<?php

  include "inc/dbconnect.php";

  

  $idLabel = $sukudb->makeLabel('Person');
  $nameLabel = $sukudb->makeLabel('Name');
  $marriageLabel = $sukudb->makeLabel('Marriage');

  $query_string = "LOAD CSV WITH HEADERS FROM 'http://localhost/Taapeli/Enckell_indi.csv' AS csvfile 
    CREATE (a:Person {
       id: csvfile.Henkilö, 
       sex: csvfile.Sukupuoli, 
       birth_date: csvfile.Syntymäaika, 
       death_date: csvfile.Kuolinaika
    }) 
    CREATE (b:Name {
      first_name: csvfile.Etunimi, 
      last_name: csvfile.Sukunimi
    }) 
    CREATE (a)-[:HAS_NAME]->(b) 
    WITH a, csvfile WHERE csvfile.Syntymäpaikka IS NOT NULL
    MERGE (c:Place {name: csvfile.Syntymäpaikka}) 
    MERGE (a)-[:BIRTH_PLACE]->(c)
    WITH a, csvfile WHERE csvfile.Kuolinpaikka IS NOT NULL
    MERGE (d:Place {name: csvfile.Kuolinpaikka})
    MERGE (a)-[:DEATH_PLACE]->(d)
";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

  $result = $query->getResultSet();

  $query_string = "LOAD CSV WITH HEADERS FROM 'http://localhost/Taapeli/Enckell_marr.csv' AS marrfile 
    MATCH (f:Person {id: marrfile.Aviomies})
    MATCH (g:Person {id: marrfile.Vaimo})
    CREATE (e:Marriage {
       id: marrfile.Avioliitto, 
       married_date: marrfile.Päivämäärä
    }) 
    CREATE (f)-[:MARRIED_HUSBAND]->(e) 
    CREATE (g)-[:MARRIED_WIFE]->(e) 
    WITH e, marrfile WHERE marrfile.Paikka IS NOT NULL
    MERGE (h:Place {name: marrfile.Paikka})
    CREATE (e)-[:MARRIAGE_PLACE]->(h)
";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

  $result = $query->getResultSet();

  $query_string = "LOAD CSV WITH HEADERS FROM 'http://localhost/Taapeli/Enckell_chil.csv' AS chilfile 
    MATCH (i:Marriage {id: chilfile.Perhe})<-[:MARRIED_HUSBAND]-(j)
    MATCH (k:Marriage {id: chilfile.Perhe})<-[:MARRIED_WIFE]-(l)
    MATCH (m:Person {id: chilfile.Lapsi})
    CREATE (j)-[:CHILD]->(m) 
    CREATE (l)-[:CHILD]->(m) 
    CREATE (m)-[:FATHER]->(j) 
    CREATE (m)-[:MOTHER]->(l) 
";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

  $result = $query->getResultSet();

?>
