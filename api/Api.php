<?php

abstract class Api {

  private static $_Db;
  protected static $_columns;
  protected static $_where;
  protected static $_set;
  protected static $_params;
  protected static $_offset = 0;
  protected static $_limit = 1;
  protected static $_order ;
  protected static $_join ;
  private static $_jwtKey = 'key';

  private function setDb() {
    if( strpos(WEB_ROOT, 'heroku') !== false ){ // HEROKY VAR ENV
      $url = getenv('JAWSDB_URL');
      $dbparts = parse_url($url);
      $host = $dbparts['host'];
      $dbn = ltrim($dbparts['path'],'/');
      $port = $dbparts['port'];
      $usr = $dbparts['user'];
      $pwd = $dbparts['pass'];
    } else {
      $host = 'localhost';
      $dbn = 'hedwige';
      $port = 8889;
      $usr = 'root';
      $pwd = 'root';
    }
    try {
      self::$_Db = $pdo = new PDO("mysql:host=$host;dbname=$dbn;port=$port", $usr, $pwd);
      self::$_Db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }

  }

  protected function getDb(): PDO {
    if( self::$_Db == null )
      self::setDb();
    return self::$_Db;
  }

  private function getColumns($columns) {

    if( $columns === null ) return [];
    if( isset($_GET['fields']) && !empty($_GET['fields']) ) {
      $fields = explode(',', $_GET['fields']);
      self::$_columns = array_intersect($fields, $columns);

      if( count(self::$_columns) === 0 ){
        http_response_code(400);
        return [];
      }
    } else {
      self::$_columns = $columns;

    }
  }

  // SELECT
  protected function get($table, $columns=null): array {

    // COLUMNS
    $this->getColumns($columns);
    $sql = "SELECT " . join(', ', self::$_columns) . " FROM $table" ;

    /// INNER
      /* $_join = [[
       *    'type' => inner, left ou right
       *    'table' => TABLE2
       *    'onT1' => TABLE1.col
       *    'onT2' => TABLE2.col
       * ]]
      */
    if (isset(self::$_join) && !empty(self::$_join)) {
        foreach (self::$_join as $join) {
            $joinClause = ' ' . strtoupper($join['type']) . ' JOIN ' . $join['table'] . ' ON ' . $join['onT1'] . ' = ' . $join['onT2'] ;
            $sql .= $joinClause ;
        }
    }

    // WHERE
    if( isset(self::$_where) && count(self::$_where) > 0 ) {
      $whereClause = join(' AND ', self::$_where);
      $sql .= ' WHERE ' . $whereClause;
    }

    // ORDER BY
    if (isset(self::$_order)) {
        $sql .= ' ORDER BY ' . self::$_order ;
    }

    // LIMIT
    self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
    self::$_limit = self::$_limit > 50 ? 50 : self::$_limit;
    $sql .= " LIMIT " . self::$_offset .', '. self::$_limit;

    $stmt = $this->getDb()->prepare($sql);
    if($stmt) {
      $success = $stmt->execute(self::$_params);
      if ($success) {
        $this->resetParams();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        http_response_code(500) ;
      }
    } else {
      http_response_code(500) ;
    }
  }


  // INSERT
  protected function add($table) {

    $cols = '( ' . join(', ', self::$_columns) . ' )';
    $values = [];
    foreach (self::$_columns as $col)  $values[] = '?';
    $values = '( ' . join(', ', $values) . ' )';
    $sql = 'INSERT INTO ' . $table . $cols . ' VALUES' . $values ;

    $connect = $this->getDb();
    $stmt = $connect->prepare($sql);
    if($stmt) {
      $success = $stmt->execute(self::$_params);
      if ($success) {
        $this->resetParams();
        http_response_code(200);
        return $connect->LastInsertId();
      } else {
        http_response_code(500) ;
      }
    } else
      http_response_code(500) ;
    }

  // UPDATE
  protected function patch($table, $id) {

      // UPDATE `CLIENT` SET name = ?, website = ? WHERE CLIENT.id = 2
      $sql = "UPDATE " . $table;

      // SET
      if( isset(self::$_set) && count(self::$_set) > 0 ) {
        $setClause = join(', ', self::$_set);
        $sql .= ' SET ' . $setClause;
      } else {
        // bad parameters
        http_response_code(400);
        exit();
      }

      $sql .=  " WHERE id = $id";

      $stmt = $this->getDb()->prepare($sql);
      if ($stmt) {
        $success = $stmt->execute(self::$_params);
        if ($success) {
          // OK
          $this->resetParams();
          http_response_code(200);
        } else {
          http_response_code(500);
        }
      } else {
        http_response_code(500);
      }
    }

    protected function delete($table, $id) {
        $sql = "DELETE FROM $table WHERE id = ?";
        self::$_params[] = $id;

        $stmt = $this->getDb()->prepare($sql);
        if ($stmt) {
          $success = $stmt->execute(self::$_params);
          if ($success) {
            // OK
            $this->resetParams();
            http_response_code(200);
          } else {
            http_response_code(500);
          }
        } else {
          http_response_code(500);
        }

    }



  protected function resetParams() {
    self::$_columns = [];
    self::$_where = [];
    self::$_params = [];
    self::$_offset = 0;
    self::$_limit = 1;
    self::$_order = NULL ;
    self::$_join = [] ;
  }

  // RECOVER POST DATA
  protected function getPostJson() {
    return json_encode(json_decode($content), JSON_PRETTY_PRINT);
  }

  protected function getJsonArray() {
    return json_decode(file_get_contents('php://input'), true);
  }

  // AUTH
  protected function generateJWT($id, $role,$exp) {

    $header = ['alg' => 'HS256',
               'typ' => 'JWT'
              ];
    $playload = ['sub' => $id,
                 'exp' => time() + $exp,
                 'role' => $role
                ];
    $headerJson = json_encode($header);
    $playloadJson = json_encode($playload);

    $signature = hash_hmac('sha256', base64_encode($headerJson) . '.' . base64_encode($playloadJson) ,
                            self::$_jwtKey);

    return base64_encode($headerJson) . '.' . base64_encode($playloadJson) . '.' . $signature;
  }

  private function decodeJWT($jwt) {
    $params = explode('.', $jwt);
    if( count($params) != 3 ){
      echo 'Error JWT bad syntax';
      return false;
    }

    $decode = [
            'header' => base64_decode($params[0]),
            'playload' => base64_decode($params[1])
           ];

    if( $this->checkJWT($decode['header'], $decode['playload'], $params[2] ) )
      return $decode;
    else
      self::catError(400); // bad request
  }

  private function checkJWT($header, $playload, $signature): bool {
    if( intval(json_decode($playload, true)['exp']) < time() )
      self::catError(401); // Unauthorized -> token expired

    $hash = hash_hmac('sha256', base64_encode($header) . '.' . base64_encode($playload), self::$_jwtKey);
    return $hash == $signature;
  }

  protected function authentication ($allowedRoles=null, $allowedId=null) {
    if( $allowedId == null && $allowedRoles == null )
      return true;

    $headers = getallheaders();
    if( isset($headers['Authorization']) ){
      $checkSum = 0 ;
      $jwt = $this->decodeJWT($headers['Authorization']);
      $playload = json_decode($jwt['playload'],true);

      $playload['sub'] = intval($playload['sub']);
      $status = true;



      if( $allowedRoles && !$allowedId )
        $status = in_array($playload['role'], $allowedRoles);

      elseif ( !$allowedRoles && $allowedId )
        $status = in_array($playload['sub'], $allowedId);

      elseif ( $allowedRoles && $allowedId )
        $status = in_array($playload['role'], $allowedRoles) && in_array($playload['sub'], $allowedId);

      if( !$status )
        self::catError(401);

    }
    else
      self::catError(400);

  }

  protected function catError($code){
    http_response_code($code);
    echo '<img src="https://http.cat/'.$code.'.jpg" alt="'.$code.'">';
    exit(0);
  }

  protected function valueExists( $table, $column, $value ) {
      if( isset($table, $column) ){
          self::$_columns = ['id'];
          self::$_where = ["$column = ?"];
          self::$_params = [$value];
          $clients = $this->get($table);
          return count($clients) > 0;
      }else return -1;
  }

    protected function isValueCorrect( $id, $table, $column, $value ) {
        if( isset($id, $table, $column, $value) ) {
            self::$_columns = ['id'];
            self::$_where = [ 'id = ?',"$column = ?"];
            self::$_params = [$id, $value];
            $clients = $this->get($table);
            return count($clients) > 0;
        } else return -1;
    }
}
