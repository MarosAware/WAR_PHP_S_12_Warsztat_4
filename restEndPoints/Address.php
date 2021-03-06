<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    Address::setDb($db);

    if (isset($pathId)) {
        $address = Address::load($pathId);
        $response =
            [
                [
                'city' => $address->getCity(),
                'street' => $address->getStreet(),
                'code' => $address->getCode(),
                'flat' => $address->getFlat()
                ]
            ];
    } else {
        $response = Address::loadAll();
    }

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    parse_str(file_get_contents("php://input"), $patchVars);
    Address::setDb($db);
    $address = new Address($db);
    $address->setCity($patchVars['city']);
    $address->setStreet($patchVars['street']);
    $address->setCode($patchVars['code']);
    $address->setFlat($patchVars['flat']);

    $result = $address->save();

    if ($result) {
        $response['success'] = $result;
    } else {
        $response['error'] = 'Can\'t save to db.';
    }

} elseif ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    Address::setDb($db);
    parse_str(file_get_contents("php://input"), $patchVars);
    $address = Address::load($patchVars['id']);
    $address->setCity($patchVars['city']);
    $address->setStreet($patchVars['street']);
    $address->setCode($patchVars['code']);
    $address->setFlat($patchVars['flat']);

    $result = $address->update();

    if ($result) {
        $response['success'] = $result;
    } else {
        $response['error'] = 'Can\'t save to db.';
    }

} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $patchVars);
    Address::setDb($db);
    $result = Address::delete($patchVars['id']);

    if ($result) {
        $response['success'] = $result;
    } else {
        $response['error'] = 'Can\'t delete.';
    }

} else {
    $response['error'] = 'Wrong request method';
}
