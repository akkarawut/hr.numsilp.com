<?php
class Version {
  /**
  | Group | version
  | Description | 1.เพิ่ม version
  | $code | code
  | $name | name
  | $bundle_id | bundle_id
  | $application_version | application_version
  | $appstore_id | appstore_id
   */
  public static function addVersion($code, $name, $bundle_id, $application_version, $appstore_id) {
    $data = [
      'name'                => $name,
      'bundle_id'           => $bundle_id,
      'application_version' => $application_version,
      'appstore_id'         => $appstore_id
    ];

    $insert = Amst::insert($code.'_version', $data);
    if (!$insert) {
      return Amst::repError('Fail to add version');
    }

    return Amst::repSuccess();
  }

  /**
  | Group | version
  | Description | 2.ลบ version
  | $code | code
  | $id | id
   */
  public static function deleteVersion($code, $id) {
    $where = ['id' => $id];
    $delete = Amst::delete($code.'_version', $where);

    return Amst::repSuccess();
  }

  /**
  | Group | version
  | Description | 3.แก้ไข version
  | $code | code
  | $id | id
  | $name | name
  | $bundle_id | bundle_id
  | $application_version | application_version
  | $appstore_id | appstore_id
   */
  public static function editVersion($code, $id, $name, $bundle_id, $application_version, $appstore_id) {
    $data = [
      'name'                => $name,
      'bundle_id'           => $bundle_id,
      'application_version' => $application_version,
      'appstore_id'         => $appstore_id
    ];

    $where = ['id' => $id];
    $update = Amst::update($code.'_version', $data, $where);
    if (!$update) {
      return Amst::repError('Fail to update version');
    }

    return Amst::repSuccess();
  }

  /**
  | Group | version
  | Description | 4.getbyid
  | $code | code
  | $id | id
   */
  public static function getByID($code, $id) {
    $where = ['id' => $id];
    $version_info = Amst::get($code.'_version', '*', $where);
    if (!$version_info) {
      return Amst::repError('Fail to get version');
    }

    return Amst::repSuccess($version_info);
  }

  /**
  | Group | version
  | Description | 5.getby bundle_id
  | $code | code
  | $bundle_id | bundle_id
   */
  public static function getByBundleId($code, $bundle_id = '') {
    $where = ['bundle_id' => $bundle_id];
    $version_info = Amst::get($code.'_version', '*', $where);
    if (!$version_info) {
      return Amst::repError('Fail to get version');
    }

    return Amst::repSuccess($version_info);
  }

  /**
  | Group | version
  | Description | 6.select
  | $code | code
  | $where | where
   */
  public static function getList($code, $where = []) {
    $version_list = Amst::selectNoKey($code.'_version', '*', $where);
    if (!$version_list) {
      return Amst::repError('Fail to getlist version');
    }

    return Amst::repSuccess($version_list);
  }

  /**
  | Group | version
  | Description | 7.RETURN 1 = OK , 0 = Required Update , -1 = Server offline
  | $code | code
  | $bundle_id | bundle_id
  | $version | version
   */
  public static function checkVersion($code, $bundle_id, $version) {
    $where = [
      'AND' => [
        'system_delete' => 0,
        'bundle_id'     => $bundle_id

      ]
    ];

    $getVersion = Amst::get($code.'_version', '*', $where);

    if ($getVersion) {
      $device_version = explode(".", $version);
      $cloud_version = explode(".", $getVersion['application_version']);

      $getVersion['device_version'] = $device_version;
      $getVersion['cloud_version'] = $cloud_version;

      $result = [];

      if ($getVersion['application_version'] == '0') {
        $result['status'] = -1;
        $result['data'] = $getVersion;
        return Amst::repSuccess($result);
      } else if (count($device_version) != count($cloud_version)) {
        $result['status'] = 1;
        $result['data'] = $getVersion;
        return Amst::repSuccess($result);
      } else {
        // for ($i = 0; $i < count($cloud_version); $i++) {
        //   if (!is_numeric($device_version[$i])) {
        //     $result['status'] = 1;
        //     $result['data']   = $getVersion;
        //     return Amst::repSuccess($result);
        //   }

        //   $device = (int) $device_version[$i];
        //   $cloud  = (int) $cloud_version[$i];

        //   if ($device < $cloud) {
        //     $result['status'] = 0;
        //     $result['data']   = $getVersion;
        //     return Amst::repSuccess($result);
        //   }
        // }

        $check = version_compare($getVersion['application_version'], $version);
        if ($check == 1) {
          $result['status'] = 0;
          $result['data'] = $getVersion;
          return Amst::repSuccess($result);
        }

        $result['status'] = 1;
        $result['data'] = $getVersion;
        return Amst::repSuccess($result);
      }
    } else {
      return Amst::repError('ไม่พบ Bundle ID นี้');
    }
  }
}
