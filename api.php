<?php
// API mínima para tickets (PHP 7.4+). Endpoints:
// GET    api.php?fn=list              -> lista todos
// GET    api.php?id=1                 -> obtiene uno
// POST   api.php                      -> crea (JSON en body)
// PUT    api.php?id=1                 -> actualiza (JSON en body)
// DELETE api.php?id=1                 -> elimina

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

require_once __DIR__ . '/config.php';
$pdo = db();

function json_body() {
  $raw = file_get_contents('php://input');
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

try {
  $method = $_SERVER['REQUEST_METHOD'];

  if ($method === 'GET') {
    if (isset($_GET['id'])) {
      $stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = ?');
      $stmt->execute([intval($_GET['id'])]);
      echo json_encode($stmt->fetch(PDO::FETCH_ASSOC) ?: new stdClass());
      exit;
    }
    // list
    $stmt = $pdo->query('SELECT * FROM tickets ORDER BY id DESC');
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
  }

  if ($method === 'POST') {
    $d = json_body();

    // Validaciones básicas
    foreach (['fecha','area','quien_solicita','tema','solicitud'] as $req) {
      if (!isset($d[$req]) || $d[$req] === '') {
        http_response_code(422);
        echo json_encode(['ok'=>false,'error'=>"Falta el campo requerido: $req"]);
        exit;
      }
    }

    $sql = 'INSERT INTO tickets (mes, fecha, area, centro_costo, quien_solicita, tema, solicitud, solucion, metodo, fecha_rta, estado)
            VALUES (:mes, :fecha, :area, :centro_costo, :quien_solicita, :tema, :solicitud, :solucion, :metodo, :fecha_rta, :estado)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':mes' => $d['mes'] ?? null,
      ':fecha' => $d['fecha'] ?? null,
      ':area' => $d['area'] ?? null,
      ':centro_costo' => $d['centro_costo'] ?? null,
      ':quien_solicita' => $d['quien_solicita'] ?? null,
      ':tema' => $d['tema'] ?? null,
      ':solicitud' => $d['solicitud'] ?? null,
      ':solucion' => $d['solucion'] ?? null,
      ':metodo' => $d['metodo'] ?? null,
      ':fecha_rta' => $d['fecha_rta'] ?? null,
      ':estado' => $d['estado'] ?? 'Pendiente',
    ]);
    echo json_encode(['ok'=>true,'id'=>$pdo->lastInsertId()]);
    exit;
  }

  if ($method === 'PUT') {
    if (!isset($_GET['id'])) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Falta id']); exit; }
    $id = intval($_GET['id']);
    $d = json_body();
    if (!$d) { echo json_encode(['ok'=>false,'error'=>'Sin datos']); exit; }

    // construir SET dinámico
    $allowed = ['mes','fecha','area','centro_costo','quien_solicita','tema','solicitud','solucion','metodo','fecha_rta','estado'];
    $fields = []; $params = [];
    foreach ($allowed as $k) {
      if (array_key_exists($k, $d)) {
        $fields[] = "$k = :$k";
        $params[":$k"] = $d[$k];
      }
    }
    if (!$fields) { echo json_encode(['ok'=>false,'error'=>'Nada para actualizar']); exit; }
    $params[':id'] = $id;
    $sql = 'UPDATE tickets SET ' . implode(', ', $fields) . ' WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode(['ok'=>true]);
    exit;
  }

  if ($method === 'DELETE') {
    if (!isset($_GET['id'])) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Falta id']); exit; }
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare('DELETE FROM tickets WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['ok'=>true]);
    exit;
  }

  http_response_code(405);
  echo json_encode(['ok'=>false,'error'=>'Método no permitido']);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}
