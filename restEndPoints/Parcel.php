<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    Parcel::setDb($db);

    if (isset($pathId)) {
        $parcel = Parcel::load($pathId);
        $response =
            [
                [
                    'name' => $parcel->getName(),
                    'address' => $parcel->getAddress(),
                    'size' => $parcel->getSize()
                ]
            ];
    } else {
        $response = Parcel::loadAll();
    }

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    parse_str(file_get_contents("php://input"), $patchVars);
    Parcel::setDb($db);
    $parcel = new Parcel();
    $parcel->setName($patchVars['user_id']);
    $parcel->setAddress($patchVars['address_id']);
    $parcel->setSize($patchVars['size_id']);

    $result = $parcel->save();

    if ($result) {
        $response['success'] = $result;
    } else {
        $response['error'] = 'Can\'t save to db.';
    }

} elseif ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    Parcel::setDb($db);
    parse_str(file_get_contents("php://input"), $patchVars);
    $parcel = Parcel::load($patchVars['id']);
    $parcel->setName($patchVars['name']);
    $parcel->setAddress($patchVars['address']);
    $parcel->setSize($patchVars['size']);

    $result = $parcel->update();

    if ($result) {
        $response['success'] = $result;
    } else {
        $response['error'] = 'Can\'t save to db.';
    }

} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $patchVars);
    Parcel::setDb($db);
    $result = Parcel::delete($patchVars['id']);

    if ($result) {
        $response['success'] = $result;
    } else {
        $response['error'] = 'Can\'t delete.';
    }

} else {
    $response['error'] = 'Wrong request method';
}
