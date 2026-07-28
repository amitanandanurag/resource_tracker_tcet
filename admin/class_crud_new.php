<?php ob_start();
include "header/header.php"; ?>
<?php
$masters = array(

  'class' => array(
    'title' => 'Class',
    'table' => 'rt_class_master',
    'pk'    => 'class_id',
    'name'  => 'class_name'
  ),

  'department' => array(
    'title' => 'Department',
    'table' => 'rt_department_master',
    'pk'    => 'department_id',
    'name'  => 'department_name'
  ),

  'division' => array(
    'title' => 'Division',
    'table' => 'rt_division_master',
    'pk'    => 'division_id',
    'name'  => 'division_name'
  ),

  'role' => array(
    'title' => 'Role',
    'table' => 'rt_role_master',
    'pk'    => 'role_id',
    'name'  => 'role_name'
  ),

  'menu' => array(
    'title' => 'Menu',
    'table' => 'rt_menu_master',
    'pk'    => 'menu_id',
    'name'  => 'menu_name'
  ),

  'sub_menu' => array(
    'title' => 'Sub Menu',
    'table' => 'rt_sub_menu_master',
    'pk'    => 'sub_menu_id',
    'name'  => 'sub_menu_name'
  ),

  'user' => array(
    'title' => 'Users',
    'table' => 'rt_user_master',
    'pk'    => 'user_id',
    'name'  => 'first_name'
  ),

  'userlog' => array(
    'title' => 'User Logs',
    'table' => 'rt_user_log_master',
    'pk'    => 'user_log_id',
    'name'  => 'user_id'
  ),

  //'menuallocation' => array(
  //  'title' => 'Menu Allocation',
  //  'table' => 'rt_menu_allocation_master',
   // 'pk'    => 'menu_allocation_id',
  //  'name'  => 'role_id'
  //)

);
$type = $_GET['type'] ?? '';
$tab  = $_GET['tab'] ?? '';

if ($tab === 'sub-menu-list') {
  $type = 'sub_menu';
}

if ($tab === 'menu-list') {
  $type = 'menu';
}

if ($tab === 'class-list') {
  $type = 'class';
}

if ($tab === 'division-list') {
  $type = 'division';
}

if ($tab === 'department-list') {
  $type = 'department';
}

if ($tab === 'role-list') {
  $type = 'role';
}

if ($tab === 'user-list') {
  $type = 'user';
}

if ($tab === 'userlog-list') {
  $type = 'userlog';
}

if (!isset($masters[$type])) {
  $type = 'class';
}

$activeMaster = $type;
$masterRows = [];

foreach ($masters as $key => $meta) {

  $tableName = $meta['table'];
  $pkCol     = $meta['pk'];
  $nameCol   = $meta['name'];

  $masterRows[$key] = [];

  $extraCol = "";

  if ($key == "menu") {
    $extraCol = ", menu_icon";
  }

//   echo $key;

//   echo "<pre>";

// echo "KEY         : " . $key . "\n";
// echo "TABLE       : " . $tableName . "\n";
// echo "PK COLUMN   : " . $pkCol . "\n";
// echo "NAME COLUMN : " . $nameCol . "\n";
// echo "EXTRA COL   : " . ($extraCol == "" ? "NONE" : $extraCol) . "\n";

// echo "SQL QUERY   : \n";
// echo $sql . "\n";

// echo "----------------------------------\n";

// echo "</pre>";

  $sql = "SELECT $pkCol AS master_id,
                   $nameCol AS master_name
            FROM $tableName
            WHERE status = 1";

  $result = mysqli_query($db_handle->conn, $sql);

if (!$result) {

    // echo "<pre>";
    // echo $key . "\n";
    // echo $sql . "\n";
    // echo mysqli_error($db_handle->conn);
    // echo "</pre>";

    continue;
}
  if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

      $masterRows[$key][] = $row;
    }
  }
}


function clean_master_value($value)
{
  $value = trim((string) $value);
  $value = preg_replace('/\s+/', ' ', $value);
  return $value;
}

function clean_sort_order($value)
{
  $value = trim((string) $value);
  return ($value === '') ? 0 : max(0, intval($value));
}

function clean_route_value($value)
{
  $value = trim((string) $value);
  return ($value === '') ? '#' : $value;
}

function get_menu_icon_options()
{
  return array(
    'fa fa-folder',
    'fa fa-dashboard',
    'fa fa-cogs',
    'fa fa-cog',
    'fa fa-users',
    'fa fa-user',
    'fa fa-user-secret',
    'fa fa-graduation-cap',
    'fa fa-book',
    'fa fa-list-alt',
    'fa fa-info-circle',
    'fa fa-plus-circle',
    'fa fa-minus-circle',
    'fa fa-history',
    'fa fa-calendar',
    'fa fa-file-text',
    'fa fa-edit',
    'fa fa-wrench',
    'fa fa-briefcase',
    'fa fa-building',
    'fa fa-university',
    'fa fa-envelope',
    'fa fa-bell',
    'fa fa-sliders',
    'fa fa-check-circle',
    'fa fa-angle-double-right'
  );
}

function sanitize_icon_class($iconClass, $default)
{
  $iconClass = trim((string) $iconClass);
  if ($iconClass === '') {
    return $default;
  }

  if (!preg_match('/^[a-z0-9\- ]+$/i', $iconClass)) {
    return $default;
  }

  return $iconClass;
}

function get_default_menu_icon($menuName)
{
  $menuName = strtolower(trim((string) $menuName));
  $map = array(
    'students' => 'fa fa-graduation-cap',
    'admin' => 'fa fa-user-secret',
    'coordinator' => 'fa fa-users',
    'mentor' => 'fa fa-user',
    'settings' => 'fa fa-book'
  );

  return isset($map[$menuName]) ? $map[$menuName] : 'fa fa-folder';
}

function get_default_submenu_icon($subMenuName)
{
  $subMenuName = strtolower(trim((string) $subMenuName));
  $map = array(
    'register students' => 'fa fa-plus',
    'list of students' => 'fa fa-info-circle',
    'concise details' => 'fa fa-info-circle',
    'left students' => 'fa fa-minus-circle',
    'previous students' => 'fa fa-history',
    'register admin' => 'fa fa-plus',
    'admin info' => 'fa fa-info-circle',
    'register coordinator' => 'fa fa-plus',
    'coordinator info' => 'fa fa-info-circle',
    'register mentor' => 'fa fa-plus',
    'mentor info' => 'fa fa-info-circle',
    'manage class' => 'fa fa-cogs',
    'manage section' => 'fa fa-list-alt'
  );

  return isset($map[$subMenuName]) ? $map[$subMenuName] : 'fa fa-angle-double-right';
}

function get_default_submenu_route($subMenuName)
{
  $subMenuName = strtolower(trim((string) $subMenuName));
  $map = array(
    'register students' => 'student_admission.php',
    'list of students' => 'student-info.php',
    'concise details' => 'student_concise_details.php',
    'left students' => '#',
    'previous students' => '#',
    'register admin' => 'admin_register.php',
    'admin info' => 'admin_info.php',
    'register coordinator' => 'coordinator_register.php',
    'coordinator info' => 'coordinator_info.php',
    'register mentor' => 'mentor_register.php',
    'mentor info' => 'mentor_info.php',
    'manage class' => 'class_crud_new.php',
    'manage section' => 'class_crud_new.php#division-list'
  );

  return isset($map[$subMenuName]) ? $map[$subMenuName] : '#';
}

function ensure_menu_metadata_columns($conn)
{
  $columnChecks = array(
    array(
      'table' => 'rt_menu_master',
      'column' => 'menu_icon',
      'alter' => "ALTER TABLE rt_menu_master ADD COLUMN menu_icon VARCHAR(100) NOT NULL DEFAULT 'fa fa-folder' AFTER menu_name"
    ),
    array(
      'table' => 'rt_sub_menu_master',
      'column' => 'sub_menu_icon',
      'alter' => "ALTER TABLE rt_sub_menu_master ADD COLUMN sub_menu_icon VARCHAR(100) NOT NULL DEFAULT 'fa fa-angle-double-right' AFTER sub_menu_name"
    ),
    array(
      'table' => 'rt_sub_menu_master',
      'column' => 'sub_menu_route',
      'alter' => "ALTER TABLE rt_sub_menu_master ADD COLUMN sub_menu_route VARCHAR(255) NOT NULL DEFAULT '#' AFTER sub_menu_icon"
    )
  );

  foreach ($columnChecks as $check) {
    $table = $check['table'];
    $column = $check['column'];
    $escapedColumn = mysqli_real_escape_string($conn, $column);
    $existsResult = mysqli_query($conn, "SHOW COLUMNS FROM $table LIKE '$escapedColumn'");
    $exists = ($existsResult && mysqli_num_rows($existsResult) > 0);

    if ($existsResult) {
      mysqli_free_result($existsResult);
    }

    if (!$exists) {
      mysqli_query($conn, $check['alter']);
    }
  }

  $menuResult = mysqli_query($conn, "SELECT menu_id, menu_name, menu_icon FROM rt_menu_master");
  if ($menuResult) {
    while ($menuRow = mysqli_fetch_assoc($menuResult)) {
      $menuId = intval($menuRow['menu_id']);
      $menuIcon = trim((string) ($menuRow['menu_icon'] ?? ''));
      if ($menuIcon === '') {
        $menuIcon = get_default_menu_icon($menuRow['menu_name'] ?? '');
        $updateStmt = mysqli_prepare($conn, "UPDATE rt_menu_master SET menu_icon = ? WHERE menu_id = ?");
        if ($updateStmt) {
          mysqli_stmt_bind_param($updateStmt, 'si', $menuIcon, $menuId);
          mysqli_stmt_execute($updateStmt);
          mysqli_stmt_close($updateStmt);
        }
      }
    }
    mysqli_free_result($menuResult);
  }

  $subMenuResult = mysqli_query($conn, "SELECT sub_menu_id, sub_menu_name, sub_menu_icon, sub_menu_route FROM rt_sub_menu_master");
  if ($subMenuResult) {
    while ($subMenuRow = mysqli_fetch_assoc($subMenuResult)) {
      $subMenuId = intval($subMenuRow['sub_menu_id']);
      $subMenuIcon = trim((string) ($subMenuRow['sub_menu_icon'] ?? ''));
      $subMenuRoute = trim((string) ($subMenuRow['sub_menu_route'] ?? ''));
      $needsUpdate = false;

      if ($subMenuIcon === '') {
        $subMenuIcon = get_default_submenu_icon($subMenuRow['sub_menu_name'] ?? '');
        $needsUpdate = true;
      }
      if ($subMenuRoute === '') {
        $subMenuRoute = get_default_submenu_route($subMenuRow['sub_menu_name'] ?? '');
        $needsUpdate = true;
      }

      if ($needsUpdate) {
        $updateStmt = mysqli_prepare($conn, "UPDATE rt_sub_menu_master SET sub_menu_icon = ?, sub_menu_route = ? WHERE sub_menu_id = ?");
        if ($updateStmt) {
          mysqli_stmt_bind_param($updateStmt, 'ssi', $subMenuIcon, $subMenuRoute, $subMenuId);
          mysqli_stmt_execute($updateStmt);
          mysqli_stmt_close($updateStmt);
        }
      }
    }
    mysqli_free_result($subMenuResult);
  }
}

function ensure_parent_menu_allocation_for_roles($conn, $menuId)
{
  $roleIds = array(1, 2, 3, 4);
  foreach ($roleIds as $roleId) {
    $checkSql = "SELECT 1 FROM rt_menu_allocation_master WHERE user_id = 0 AND role_id = ? AND menu_id = ? AND sub_menu_id IS NULL LIMIT 1";
    $checkStmt = mysqli_prepare($conn, $checkSql);

    if ($checkStmt) {
      mysqli_stmt_bind_param($checkStmt, 'ii', $roleId, $menuId);
      mysqli_stmt_execute($checkStmt);
      $checkResult = mysqli_stmt_get_result($checkStmt);
      $exists = ($checkResult && mysqli_num_rows($checkResult) > 0);
      mysqli_stmt_close($checkStmt);

      if (!$exists) {
        $insertSql = "INSERT INTO rt_menu_allocation_master (user_id, role_id, menu_id, sub_menu_id) VALUES (0, ?, ?, NULL)";
        $insertStmt = mysqli_prepare($conn, $insertSql);
        if ($insertStmt) {
          mysqli_stmt_bind_param($insertStmt, 'ii', $roleId, $menuId);
          mysqli_stmt_execute($insertStmt);
          mysqli_stmt_close($insertStmt);
        }
      }
    }
  }
}

function ensure_sub_menu_allocation_for_roles($conn, $menuId, $subMenuId)
{
  $roleIds = array(1, 2, 3, 4);
  ensure_parent_menu_allocation_for_roles($conn, $menuId);

  foreach ($roleIds as $roleId) {
    $checkSql = "SELECT 1 FROM rt_menu_allocation_master WHERE user_id = 0 AND role_id = ? AND menu_id = ? AND sub_menu_id = ? LIMIT 1";
    $checkStmt = mysqli_prepare($conn, $checkSql);

    if ($checkStmt) {
      mysqli_stmt_bind_param($checkStmt, 'iii', $roleId, $menuId, $subMenuId);
      mysqli_stmt_execute($checkStmt);
      $checkResult = mysqli_stmt_get_result($checkStmt);
      $exists = ($checkResult && mysqli_num_rows($checkResult) > 0);
      mysqli_stmt_close($checkStmt);

      if (!$exists) {
        $insertSql = "INSERT INTO rt_menu_allocation_master (user_id, role_id, menu_id, sub_menu_id) VALUES (0, ?, ?, ?)";
        $insertStmt = mysqli_prepare($conn, $insertSql);
        if ($insertStmt) {
          mysqli_stmt_bind_param($insertStmt, 'iii', $roleId, $menuId, $subMenuId);
          mysqli_stmt_execute($insertStmt);
          mysqli_stmt_close($insertStmt);
        }
      }
    }
  }
}

function normalize_sub_menu_sequence_by_menu($conn)
{
  $menuIds = array();
  $menuResult = mysqli_query($conn, "SELECT menu_id FROM rt_menu_master ORDER BY menu_id ASC");
  if ($menuResult) {
    while ($menuRow = mysqli_fetch_assoc($menuResult)) {
      $menuId = (int) ($menuRow['menu_id'] ?? 0);
      if ($menuId > 0) {
        $menuIds[] = $menuId;
      }
    }
    mysqli_free_result($menuResult);
  }

  foreach ($menuIds as $menuId) {
    $subSql = "SELECT sub_menu_id, COALESCE(sort_order, 0) AS sort_order
               FROM rt_sub_menu_master
               WHERE menu_id = ?
               ORDER BY sort_order ASC, sub_menu_id ASC";
    $subStmt = mysqli_prepare($conn, $subSql);
    if (!$subStmt) {
      continue;
    }

    mysqli_stmt_bind_param($subStmt, 'i', $menuId);
    mysqli_stmt_execute($subStmt);
    $subResult = mysqli_stmt_get_result($subStmt);

    $expectedOrder = 1;
    while ($subResult && ($subRow = mysqli_fetch_assoc($subResult))) {
      $subMenuId = (int) ($subRow['sub_menu_id'] ?? 0);
      $currentOrder = (int) ($subRow['sort_order'] ?? 0);

      if ($subMenuId > 0 && $currentOrder !== $expectedOrder) {
        $updateSql = "UPDATE rt_sub_menu_master SET sort_order = ? WHERE sub_menu_id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        if ($updateStmt) {
          mysqli_stmt_bind_param($updateStmt, 'ii', $expectedOrder, $subMenuId);
          mysqli_stmt_execute($updateStmt);
          mysqli_stmt_close($updateStmt);
        }
      }

      $expectedOrder++;
    }

    mysqli_stmt_close($subStmt);
  }
}

$activeTab = 'class-list';
$alertType = '';
$alertMessage = '';
$openAddModalType = '';
$openAddSubMenuModal = false;
$shouldSyncSidebar = false;
$ajaxResponse = null;
$isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

ensure_menu_metadata_columns($db_handle->conn);

$availableMenuIcons = get_menu_icon_options();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['master_action'], $_POST['master_type'])) {
  $masterType = $_POST['master_type'];
  $action = $_POST['master_action'];

  if (isset($masters[$masterType])) {
    $meta = $masters[$masterType];
    $table = $meta['table'];
    $pk = $meta['pk'];
    $nameCol = $meta['name'];
    $title = $meta['title'];

    if ($action === 'add') {
      $name = clean_master_value($_POST['master_name'] ?? '');
      $menuIcon = sanitize_icon_class($_POST['menu_icon'] ?? '', get_default_menu_icon($name));
      $activeTab = $masterType . '-add';
      $openAddModalType = $masterType;

      if ($name === '') {
        $alertType = 'warning';
        $alertMessage = $title . ' name is required.';
      } else {
        $dupSql = "SELECT COUNT(*) AS cnt FROM $table WHERE LOWER(TRIM($nameCol)) = LOWER(TRIM(?))";
        $dupStmt = mysqli_prepare($db_handle->conn, $dupSql);

        if ($dupStmt) {
          mysqli_stmt_bind_param($dupStmt, 's', $name);
          mysqli_stmt_execute($dupStmt);
          $dupResult = mysqli_stmt_get_result($dupStmt);
          $dupRow = $dupResult ? mysqli_fetch_assoc($dupResult) : array('cnt' => 0);
          mysqli_stmt_close($dupStmt);

          if (!empty($dupRow) && intval($dupRow['cnt']) > 0) {
            $alertType = 'warning';
            $alertMessage = $title . ' already exists.';
          } else {
            if ($masterType === 'menu') {
              $insertSql = "INSERT INTO $table ($nameCol, menu_icon) VALUES (?, ?)";
            } else {
              $insertSql = "INSERT INTO $table ($nameCol) VALUES (?)";
            }
            $insertStmt = mysqli_prepare($db_handle->conn, $insertSql);

            if ($insertStmt) {
              if ($masterType === 'menu') {
                mysqli_stmt_bind_param($insertStmt, 'ss', $name, $menuIcon);
              } else {
                mysqli_stmt_bind_param($insertStmt, 's', $name);
              }
              $ok = mysqli_stmt_execute($insertStmt);
              mysqli_stmt_close($insertStmt);

              if ($ok) {
                $alertType = 'success';
                $alertMessage = $title . ' added successfully.';
                $activeTab = $masterType . '-list';
                $openAddModalType = '';
                $shouldSyncSidebar = true;
                $insertedId = mysqli_insert_id($db_handle->conn);

                if ($masterType === 'menu') {
                  if ($insertedId > 0) {
                    ensure_parent_menu_allocation_for_roles($db_handle->conn, $insertedId);
                  }
                }

                if ($isAjaxRequest) {
                  $ajaxResponse = array(
                    'status' => 'success',
                    'message' => $alertMessage,
                    'master_type' => $masterType,
                    'master_id' => $insertedId,
                    'master_name' => $name,
                    'menu_icon' => $menuIcon
                  );
                }
              } else {
                $alertType = 'danger';
                $alertMessage = 'Unable to add ' . strtolower($title) . '.';
                if ($isAjaxRequest) {
                  $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
                }
              }
            } else {
              $alertType = 'danger';
              $alertMessage = 'Unable to prepare add statement for ' . strtolower($title) . '.';
              if ($isAjaxRequest) {
                $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
              }
            }
          }
        } else {
          $alertType = 'danger';
          $alertMessage = 'Unable to validate duplicate ' . strtolower($title) . '.';
          if ($isAjaxRequest) {
            $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
          }
        }
      }
    } elseif ($action === 'update') {
      $id = intval($_POST['master_id'] ?? 0);
      $name = clean_master_value($_POST['master_name'] ?? '');
      $menuIcon = sanitize_icon_class($_POST['menu_icon'] ?? '', get_default_menu_icon($name));
      $activeTab = $masterType . '-list';

      if ($id <= 0 || $name === '') {
        $alertType = 'warning';
        $alertMessage = 'Valid ' . strtolower($title) . ' details are required for update.';
      } else {
        $dupSql = "SELECT COUNT(*) AS cnt FROM $table WHERE LOWER(TRIM($nameCol)) = LOWER(TRIM(?)) AND $pk <> ?";
        $dupStmt = mysqli_prepare($db_handle->conn, $dupSql);

        if ($dupStmt) {
          mysqli_stmt_bind_param($dupStmt, 'si', $name, $id);
          mysqli_stmt_execute($dupStmt);
          $dupResult = mysqli_stmt_get_result($dupStmt);
          $dupRow = $dupResult ? mysqli_fetch_assoc($dupResult) : array('cnt' => 0);
          mysqli_stmt_close($dupStmt);

          if (!empty($dupRow) && intval($dupRow['cnt']) > 0) {
            $alertType = 'warning';
            $alertMessage = $title . ' already exists.';
          } else {
            if ($masterType === 'menu') {
              $updateSql = "UPDATE $table SET $nameCol = ?, menu_icon = ? WHERE $pk = ?";
            } else {
              $updateSql = "UPDATE $table SET $nameCol = ? WHERE $pk = ?";
            }
            $updateStmt = mysqli_prepare($db_handle->conn, $updateSql);

            if ($updateStmt) {
              if ($masterType === 'menu') {
                mysqli_stmt_bind_param($updateStmt, 'ssi', $name, $menuIcon, $id);
              } else {
                mysqli_stmt_bind_param($updateStmt, 'si', $name, $id);
              }
              $ok = mysqli_stmt_execute($updateStmt);
              mysqli_stmt_close($updateStmt);

              if ($ok) {
                $alertType = 'success';
                $alertMessage = $title . ' updated successfully.';
                $shouldSyncSidebar = true;
              } else {
                $alertType = 'danger';
                $alertMessage = 'Unable to update ' . strtolower($title) . '.';
              }
            } else {
              $alertType = 'danger';
              $alertMessage = 'Unable to prepare update statement for ' . strtolower($title) . '.';
            }
          }
        } else {
          $alertType = 'danger';
          $alertMessage = 'Unable to validate duplicate ' . strtolower($title) . ' before update.';
        }
      }
    } 
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sub_menu_action'])) {
  $subMenuAction = $_POST['sub_menu_action'];
  $activeTab = 'sub-menu-list';

  if ($subMenuAction === 'add') {
    $menuId = intval($_POST['menu_id'] ?? 0);
    $subMenuName = clean_master_value($_POST['sub_menu_name'] ?? '');
    $sortOrder = clean_sort_order($_POST['sort_order'] ?? 0);
    $subMenuRoute = clean_route_value($_POST['sub_menu_route'] ?? '#');
    $subMenuIcon = sanitize_icon_class($_POST['sub_menu_icon'] ?? '', get_default_submenu_icon($subMenuName));
    $openAddSubMenuModal = true;

    if ($menuId <= 0 || $subMenuName === '') {
      $alertType = 'warning';
      $alertMessage = 'Menu and sub menu name are required.';
    } else {
      $dupSql = "SELECT COUNT(*) AS cnt FROM rt_sub_menu_master WHERE menu_id = ? AND LOWER(TRIM(sub_menu_name)) = LOWER(TRIM(?))";
      $dupStmt = mysqli_prepare($db_handle->conn, $dupSql);

      if ($dupStmt) {
        mysqli_stmt_bind_param($dupStmt, 'is', $menuId, $subMenuName);
        mysqli_stmt_execute($dupStmt);
        $dupResult = mysqli_stmt_get_result($dupStmt);
        $dupRow = $dupResult ? mysqli_fetch_assoc($dupResult) : array('cnt' => 0);
        mysqli_stmt_close($dupStmt);

        if (!empty($dupRow) && intval($dupRow['cnt']) > 0) {
          $alertType = 'warning';
          $alertMessage = 'Sub menu already exists under selected menu.';
        } else {
          if ($sortOrder <= 0) {
            $sortSql = "SELECT COALESCE(MAX(sort_order), 0) + 1 AS next_order FROM rt_sub_menu_master WHERE menu_id = ?";
            $sortStmt = mysqli_prepare($db_handle->conn, $sortSql);
            if ($sortStmt) {
              mysqli_stmt_bind_param($sortStmt, 'i', $menuId);
              mysqli_stmt_execute($sortStmt);
              $sortResult = mysqli_stmt_get_result($sortStmt);
              $sortRow = $sortResult ? mysqli_fetch_assoc($sortResult) : array('next_order' => 1);
              mysqli_stmt_close($sortStmt);
              $sortOrder = intval($sortRow['next_order']);
            }
          }

          $insertSql = "INSERT INTO rt_sub_menu_master (menu_id, sub_menu_name, sort_order, sub_menu_icon, sub_menu_route) VALUES (?, ?, ?, ?, ?)";
          $insertStmt = mysqli_prepare($db_handle->conn, $insertSql);

          if ($insertStmt) {
            mysqli_stmt_bind_param($insertStmt, 'isiss', $menuId, $subMenuName, $sortOrder, $subMenuIcon, $subMenuRoute);
            $ok = mysqli_stmt_execute($insertStmt);
            mysqli_stmt_close($insertStmt);

            if ($ok) {
              $newSubMenuId = mysqli_insert_id($db_handle->conn);
              ensure_sub_menu_allocation_for_roles($db_handle->conn, $menuId, $newSubMenuId);

              $alertType = 'success';
              $alertMessage = 'Sub menu added successfully.';
              $openAddSubMenuModal = false;
              $shouldSyncSidebar = true;
              if ($isAjaxRequest) {
                $menuNameSql = $db_handle->conn->prepare("SELECT menu_name FROM rt_menu_master WHERE menu_id = ?");
                $menuName = '';
                if ($menuNameSql) {
                  $menuNameSql->bind_param('i', $menuId);
                  $menuNameSql->execute();
                  $menuNameResult = $menuNameSql->get_result();
                  if ($menuNameResult && ($menuNameRow = $menuNameResult->fetch_assoc())) {
                    $menuName = $menuNameRow['menu_name'];
                  }
                  $menuNameSql->close();
                }

                $ajaxResponse = array(
                  'status' => 'success',
                  'message' => $alertMessage,
                  'sub_menu_id' => $newSubMenuId,
                  'menu_id' => $menuId,
                  'menu_name' => $menuName,
                  'sub_menu_name' => $subMenuName,
                  'sort_order' => $sortOrder,
                  'sub_menu_icon' => $subMenuIcon,
                  'sub_menu_route' => $subMenuRoute
                );
              }
            } else {
              $alertType = 'danger';
              $alertMessage = 'Unable to add sub menu.';
              if ($isAjaxRequest) {
                $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
              }
            }
          } else {
            $alertType = 'danger';
            $alertMessage = 'Unable to prepare add statement for sub menu.';
            if ($isAjaxRequest) {
              $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
            }
          }
        }
      } else {
        $alertType = 'danger';
        $alertMessage = 'Unable to validate duplicate sub menu.';
        if ($isAjaxRequest) {
          $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
        }
      }
    }
  } elseif ($subMenuAction === 'update') {
    $subMenuId = intval($_POST['sub_menu_id'] ?? 0);
    $menuId = intval($_POST['menu_id'] ?? 0);
    $subMenuName = clean_master_value($_POST['sub_menu_name'] ?? '');
    $sortOrder = clean_sort_order($_POST['sort_order'] ?? 0);
    $subMenuRoute = clean_route_value($_POST['sub_menu_route'] ?? '#');
    $subMenuIcon = sanitize_icon_class($_POST['sub_menu_icon'] ?? '', get_default_submenu_icon($subMenuName));

    if ($subMenuId <= 0 || $menuId <= 0 || $subMenuName === '') {
      $alertType = 'warning';
      $alertMessage = 'Valid sub menu details are required for update.';
    } else {
      $dupSql = "SELECT COUNT(*) AS cnt FROM rt_sub_menu_master WHERE menu_id = ? AND LOWER(TRIM(sub_menu_name)) = LOWER(TRIM(?)) AND sub_menu_id <> ?";
      $dupStmt = mysqli_prepare($db_handle->conn, $dupSql);

      if ($dupStmt) {
        mysqli_stmt_bind_param($dupStmt, 'isi', $menuId, $subMenuName, $subMenuId);
        mysqli_stmt_execute($dupStmt);
        $dupResult = mysqli_stmt_get_result($dupStmt);
        $dupRow = $dupResult ? mysqli_fetch_assoc($dupResult) : array('cnt' => 0);
        mysqli_stmt_close($dupStmt);

        if (!empty($dupRow) && intval($dupRow['cnt']) > 0) {
          $alertType = 'warning';
          $alertMessage = 'Sub menu already exists under selected menu.';
        } else {
          $updateSql = "UPDATE rt_sub_menu_master SET menu_id = ?, sub_menu_name = ?, sort_order = ?, sub_menu_icon = ?, sub_menu_route = ? WHERE sub_menu_id = ?";
          $updateStmt = mysqli_prepare($db_handle->conn, $updateSql);

          if ($updateStmt) {
            mysqli_stmt_bind_param($updateStmt, 'isissi', $menuId, $subMenuName, $sortOrder, $subMenuIcon, $subMenuRoute, $subMenuId);
            $ok = mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);

            if ($ok) {
              $syncSql = "UPDATE rt_menu_allocation_master SET menu_id = ? WHERE sub_menu_id = ?";
              $syncStmt = mysqli_prepare($db_handle->conn, $syncSql);
              if ($syncStmt) {
                mysqli_stmt_bind_param($syncStmt, 'ii', $menuId, $subMenuId);
                mysqli_stmt_execute($syncStmt);
                mysqli_stmt_close($syncStmt);
              }

              ensure_sub_menu_allocation_for_roles($db_handle->conn, $menuId, $subMenuId);
              $alertType = 'success';
              $alertMessage = 'Sub menu updated successfully.';
              $shouldSyncSidebar = true;
            } else {
              $alertType = 'danger';
              $alertMessage = 'Unable to update sub menu.';
            }
          } else {
            $alertType = 'danger';
            $alertMessage = 'Unable to prepare update statement for sub menu.';
          }
        }
      } else {
        $alertType = 'danger';
        $alertMessage = 'Unable to validate duplicate sub menu before update.';
      }
    }
  } elseif ($subMenuAction === 'delete') {
    $subMenuId = intval($_POST['sub_menu_id'] ?? 0);

    if ($subMenuId <= 0) {
      $alertType = 'warning';
      $alertMessage = 'Invalid sub menu selected for delete.';
      if ($isAjaxRequest) {
        $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
      }
    } else {
      $allocDeleteSql = "DELETE FROM rt_menu_allocation_master WHERE sub_menu_id = ?";
      $allocDeleteStmt = mysqli_prepare($db_handle->conn, $allocDeleteSql);
      if ($allocDeleteStmt) {
        mysqli_stmt_bind_param($allocDeleteStmt, 'i', $subMenuId);
        mysqli_stmt_execute($allocDeleteStmt);
        mysqli_stmt_close($allocDeleteStmt);
      }

      $deleteSql = "DELETE FROM rt_sub_menu_master WHERE sub_menu_id = ?";
      $deleteStmt = mysqli_prepare($db_handle->conn, $deleteSql);

      if ($deleteStmt) {
        mysqli_stmt_bind_param($deleteStmt, 'i', $subMenuId);
        $ok = mysqli_stmt_execute($deleteStmt);
        mysqli_stmt_close($deleteStmt);

        if ($ok) {
          $alertType = 'success';
          $alertMessage = 'Sub menu deleted successfully.';
          $shouldSyncSidebar = true;
          if ($isAjaxRequest) {
            $ajaxResponse = array('status' => 'success', 'message' => $alertMessage, 'sub_menu_id' => $subMenuId);
          }
        } else {
          $alertType = 'danger';
          $alertMessage = 'Unable to delete sub menu.';
          if ($isAjaxRequest) {
            $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
          }
        }
      } else {
        $alertType = 'danger';
        $alertMessage = 'Unable to prepare delete statement for sub menu.';
        if ($isAjaxRequest) {
          $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
        }
      }
    }
  }
}

if (isset($_GET['tab'])) {
  $requestedTab = trim($_GET['tab']);
  if ($requestedTab !== '') {
    $activeTab = $requestedTab;
  }
}

if ($isAjaxRequest && $ajaxResponse !== null) {
  if (ob_get_length()) {
    ob_clean();
  }
  header('Content-Type: application/json');
  echo json_encode($ajaxResponse);
  exit();
}

normalize_sub_menu_sequence_by_menu($db_handle->conn);


// foreach ($masters as $type => $meta) {
//   $currentTable = $masters[$activeMaster]['table'];
//   $currentPK = $masters[$activeMaster]['pk'];
//   $currentName = $masters[$activeMaster]['name'];
// // 1. Get table from URL, default to 'class' if empty
// $table_from_url = $_GET['table'] ?? '';

// // 2. Map the URL table name to your $masters array key
// $activeMaster = 'class'; // Default
// foreach ($masters as $key => $config) {
//     if ($config['table'] === $table_from_url) {
//         $activeMaster = $key;
//         break;
//     }
// }

// // 3. Define the variables for the query (Matches your array keys)
// $pkCol    = $masters[$activeMaster]['pk'];    // class_id, department_id, etc.
// $nameCol  = $masters[$activeMaster]['name'];  // class_name, etc.
// $tableName = $masters[$activeMaster]['table']; // rt_class_master, etc.
//   $rows = array();
//   if ($key === 'menu') {
//    $sql = "SELECT $pkCol AS master_id, $nameCol AS master_name FROM $tableName WHERE status = 1";
//     $result = $db_handle->conn->query($sql);
//    $result = $db_handle->conn->query("SELECT $currentPK AS id, $currentName AS name FROM $currentTable WHERE status = 1");
//   }
//   if ($result) {
//     while ($row = $result->fetch_assoc()) {
//       $rows[] = $row;
//     }
//   }
//   $masterRows[$type] = $rows;
// }

$menuOptions = array();
$menuResult = $db_handle->conn->query("SELECT menu_id, menu_name, COALESCE(NULLIF(TRIM(menu_icon), ''), 'fa fa-folder') AS menu_icon FROM rt_menu_master ORDER BY menu_name ASC");
if ($menuResult) {
  while ($menuRow = $menuResult->fetch_assoc()) {
    $menuOptions[] = $menuRow;
  }
}

$subMenuRows = array();
$subMenuResult = $db_handle->conn->query("SELECT sm.sub_menu_id, sm.menu_id, sm.sub_menu_name, sm.sort_order, COALESCE(NULLIF(TRIM(sm.sub_menu_icon), ''), 'fa fa-angle-double-right') AS sub_menu_icon, COALESCE(NULLIF(TRIM(sm.sub_menu_route), ''), '#') AS sub_menu_route, m.menu_name FROM rt_sub_menu_master sm INNER JOIN rt_menu_master m ON m.menu_id = sm.menu_id ORDER BY m.menu_name ASC, sm.sort_order ASC, sm.sub_menu_id ASC");
if ($subMenuResult) {
  while ($subMenuRow = $subMenuResult->fetch_assoc()) {
    $subMenuRows[] = $subMenuRow;
  }
}
?>

<div class="content-wrapper">
  <section class="content-header">
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Master CRUD</li>
    </ol>
  </section>

  <section class="content" style="margin-top: 20px;">
    <div class="box" style="padding: 10px;">
      <h3><i class="fa fa-cogs"></i> Master Data</h3>

      <?php if ($alertMessage !== '') { ?>
        <div class="alert alert-<?php echo htmlspecialchars($alertType); ?> alert-dismissible" style="margin-top: 15px;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo htmlspecialchars($alertMessage); ?>
        </div>
      <?php } ?>

      <div id="ajax-status-message" style="margin-top: 15px;"></div>

      <ul class="nav nav-tabs" style="margin-top:20px;">

          <li class="<?php echo ($activeTab === 'class-list') ? 'active' : ''; ?>">
              <a data-toggle="tab" href="#class-list">Class</a>
          </li>

          <li class="<?php echo ($activeTab === 'department-list') ? 'active' : ''; ?>">
              <a data-toggle="tab" href="#department-list">Department</a>
          </li>

          <li class="<?php echo ($activeTab === 'division-list') ? 'active' : ''; ?>">
              <a data-toggle="tab" href="#division-list">Division</a>
          </li>

          <li class="<?php echo ($activeTab === 'role-list') ? 'active' : ''; ?>">
              <a data-toggle="tab" href="#role-list">Role</a>
          </li>

          <li class="<?php echo ($activeTab === 'menu-list') ? 'active' : ''; ?>">
              <a data-toggle="tab" href="#menu-list">Menu</a>
          </li>

          <li class="<?php echo ($activeTab === 'sub_menu-list') ? 'active' : ''; ?>">
              <a data-toggle="tab" href="#sub_menu-list">Sub Menu</a>
          </li>

          <li class="<?php echo ($activeTab === 'user-list') ? 'active' : ''; ?>">
              <a data-toggle="tab" href="#user-list">Users</a>
          </li>

          <li class="<?php echo ($activeTab === 'userlog-list') ? 'active' : ''; ?>">
              <a data-toggle="tab" href="#userlog-list">User Logs</a>
          </li>

         <!-- <li class="//?php echo ($activeTab === 'menuallocation-list') ? 'active' : ''; ?>">
              <a data-toggle="tab" href="#menuallocation-list">Menu Allocation</a>
          </li>-->

        </ul>

      <div class="tab-content" style="padding-top: 20px;">

<?php foreach ($masters as $key => $meta) {

    $listTabId = $key . '-list';

    $title = $meta['title'];

    $rows = $masterRows[$key] ?? [];

    $currentTable = $meta['table'];

?>

<div id="<?php echo $listTabId; ?>"
     class="tab-pane fade <?php echo ($activeTab === $listTabId) ? 'in active' : ''; ?>">

    <div class="clearfix" style="margin-bottom: 15px;">

        <!--<button
            type="button"
            class="btn btn-success pull-right open-add-modal"
            data-toggle="modal"
            data-target="#addMasterModal"
            data-master-type="<?php echo htmlspecialchars($key); ?>"
            data-master-title="<?php echo htmlspecialchars($title); ?>">

            <i class="fa fa-plus"></i>

        </button>-->
        <?php if($key == "sub_menu"){ ?>

            <button
                type="button"
                class="btn btn-success pull-right"
                data-toggle="modal"
                data-target="#addSubMenuModal">
                <i class="fa fa-plus"></i>
            </button>

            <?php } else { ?>

            <button
                type="button"
                class="btn btn-success pull-right open-add-modal"
                data-toggle="modal"
                data-target="#addMasterModal"
                data-master-type="<?php echo htmlspecialchars($key); ?>"
                data-master-title="<?php echo htmlspecialchars($title); ?>">
                <i class="fa fa-plus"></i>
            </button>

        <?php } ?>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-striped text-center">

            <thead>

                <tr>

                    <th style="width:80px;">No.</th>

                    <th><?php echo $title; ?> Name</th>

                    <th style="width:100px;">Edit</th>

                    <th style="width:100px;">Delete</th>

                </tr>

            </thead>

            <tbody>

            <?php

            if (!empty($rows)) {

                $i = 1;

                foreach ($rows as $row) {

            ?>

                <tr>

                    <td><?php echo $i++; ?></td>

                    <td>

                        <?php

                        if ($key == 'user') {

                            echo htmlspecialchars($row['master_name']);

                        }

                        elseif ($key == 'userlog') {

                            echo "User ID : " . htmlspecialchars($row['master_name']);

                        }

                        elseif ($key == 'menuallocation') {

                            echo "Role ID : " . htmlspecialchars($row['master_name']);

                        }

                        else {

                            echo htmlspecialchars($row['master_name']);

                        }

                        ?>

                    </td>

                    <td>

                        <a href="edit_master.php?table=<?php echo $currentTable; ?>&id=<?php echo $row['master_id']; ?>"
                           class="btn btn-sm btn-primary">

                            <i class="fa fa-pencil"></i>

                        </a>

                    </td>

                    <td>

                        <a href="delete_master.php?table=<?php echo $currentTable; ?>&id=<?php echo $row['master_id']; ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this record?')">

                            <i class="fa fa-trash"></i>

                        </a>

                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="4">No Data Found</td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php } ?>

</div>
    </div>
  </section>
</div>

<div id="addMasterModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="add-master-title">Add</h4>
      </div>
      <form method="POST" class="ajax-add-form">
        <div class="modal-body">
          <input type="hidden" name="master_action" value="add">
          <input type="hidden" name="master_type" id="add_master_type" value="">

          <div class="form-group">
            <label for="add_master_name" class="control-label">Name</label>
            <input type="text" name="master_name" id="add_master_name" class="form-control" placeholder="Enter name" required>
          </div>

          <div class="form-group" id="add_menu_icon_group" style="display:none;">
            <label for="add_menu_icon" class="control-label">Menu Icon</label>
            <input type="hidden" name="menu_icon" id="add_menu_icon" value="fa fa-folder">
            <div class="icon-picker" data-target-input="add_menu_icon">
              <?php foreach ($availableMenuIcons as $iconClass) { ?>
                <button type="button" class="icon-option <?php echo ($iconClass === 'fa fa-folder') ? 'active' : ''; ?>" data-icon="<?php echo htmlspecialchars($iconClass); ?>" title="<?php echo htmlspecialchars($iconClass); ?>">
                  <i class="<?php echo htmlspecialchars($iconClass); ?>" aria-hidden="true"></i>
                </button>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="addSubMenuModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Sub Menu</h4>
      </div>
      <form method="POST" class="ajax-add-form">
        <div class="modal-body">
          <input type="hidden" name="sub_menu_action" value="add">

          <div class="form-group">
            <label for="add_sub_menu_parent" class="control-label">Menu</label>
            <select name="menu_id" id="add_sub_menu_parent" class="form-control" required>
              <option value="">Select Menu</option>
              <?php foreach ($menuOptions as $menuOption) { ?>
                <option value="<?php echo intval($menuOption['menu_id']); ?>"><?php echo htmlspecialchars($menuOption['menu_name']); ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="add_sub_menu_name" class="control-label">Sub Menu Name</label>
            <input type="text" name="sub_menu_name" id="add_sub_menu_name" class="form-control" placeholder="Enter Sub Menu Name" required>
          </div>

          <div class="form-group">
            <label for="add_sub_menu_route" class="control-label">Sub Menu Route</label>
            <input type="text" name="sub_menu_route" id="add_sub_menu_route" class="form-control" placeholder="example.php or #" value="#" required>
          </div>

          <div class="form-group">
            <label for="add_sub_menu_icon" class="control-label">Sub Menu Icon</label>
            <input type="hidden" name="sub_menu_icon" id="add_sub_menu_icon" value="fa fa-angle-double-right">
            <div class="icon-picker" data-target-input="add_sub_menu_icon">
              <?php foreach ($availableMenuIcons as $iconClass) { ?>
                <button type="button" class="icon-option <?php echo ($iconClass === 'fa fa-angle-double-right') ? 'active' : ''; ?>" data-icon="<?php echo htmlspecialchars($iconClass); ?>" title="<?php echo htmlspecialchars($iconClass); ?>">
                  <i class="<?php echo htmlspecialchars($iconClass); ?>" aria-hidden="true"></i>
                </button>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label for="add_sort_order" class="control-label">Sort Order</label>
            <input type="number" name="sort_order" id="add_sort_order" class="form-control" min="1" placeholder="Auto if blank">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<form id="delete-submenu-form" method="POST" style="display:none;">
  <input type="hidden" name="sub_menu_action" value="delete">
  <input type="hidden" name="sub_menu_id" id="delete_sub_menu_id" value="">
</form>

<div id="editSubMenuModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Sub Menu</h4>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="sub_menu_action" value="update">
          <input type="hidden" name="sub_menu_id" id="edit_sub_menu_id" value="">

          <div class="form-group">
            <label for="edit_sub_menu_parent" class="control-label">Menu</label>
            <select name="menu_id" id="edit_sub_menu_parent" class="form-control" required>
              <option value="">Select Menu</option>
              <?php foreach ($menuOptions as $menuOption) { ?>
                <option value="<?php echo intval($menuOption['menu_id']); ?>"><?php echo htmlspecialchars($menuOption['menu_name']); ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="edit_sub_menu_name" class="control-label">Sub Menu Name</label>
            <input type="text" name="sub_menu_name" id="edit_sub_menu_name" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="edit_sub_menu_route" class="control-label">Sub Menu Route</label>
            <input type="text" name="sub_menu_route" id="edit_sub_menu_route" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="edit_sub_menu_icon" class="control-label">Sub Menu Icon</label>
            <input type="hidden" name="sub_menu_icon" id="edit_sub_menu_icon" value="fa fa-angle-double-right">
            <div class="icon-picker" data-target-input="edit_sub_menu_icon">
              <?php foreach ($availableMenuIcons as $iconClass) { ?>
                <button type="button" class="icon-option <?php echo ($iconClass === 'fa fa-angle-double-right') ? 'active' : ''; ?>" data-icon="<?php echo htmlspecialchars($iconClass); ?>" title="<?php echo htmlspecialchars($iconClass); ?>">
                  <i class="<?php echo htmlspecialchars($iconClass); ?>" aria-hidden="true"></i>
                </button>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label for="edit_sort_order" class="control-label">Sequence</label>
            <input type="number" name="sort_order" id="edit_sort_order" class="form-control" min="1" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<form id="delete-master-form" method="POST" style="display:none;">
  <input type="hidden" name="master_action" value="delete">
  <input type="hidden" name="master_type" id="delete_master_type" value="">
  <input type="hidden" name="master_id" id="delete_master_id" value="">
</form>

<div id="editMasterModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="edit-master-title">Edit</h4>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="master_action" value="update">
          <input type="hidden" name="master_type" id="edit_master_type" value="">
          <input type="hidden" name="master_id" id="edit_master_id" value="">

          <div class="form-group">
            <label for="edit_master_name" class="control-label">Name</label>
            <input type="text" name="master_name" id="edit_master_name" class="form-control" required>
          </div>

          <div class="form-group" id="edit_menu_icon_group" style="display:none;">
            <label for="edit_menu_icon" class="control-label">Menu Icon</label>
            <input type="hidden" name="menu_icon" id="edit_menu_icon" value="fa fa-folder">
            <div class="icon-picker" data-target-input="edit_menu_icon">
              <?php foreach ($availableMenuIcons as $iconClass) { ?>
                <button type="button" class="icon-option <?php echo ($iconClass === 'fa fa-folder') ? 'active' : ''; ?>" data-icon="<?php echo htmlspecialchars($iconClass); ?>" title="<?php echo htmlspecialchars($iconClass); ?>">
                  <i class="<?php echo htmlspecialchars($iconClass); ?>" aria-hidden="true"></i>
                </button>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .icon-picker {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    max-height: 180px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px;
  }

  .icon-picker .icon-option {
    width: 36px;
    height: 36px;
    border: 1px solid #d2d6de;
    border-radius: 4px;
    background: #fff;
    color: #333;
    cursor: pointer;
  }

  .icon-picker .icon-option:hover {
    border-color: #3c8dbc;
  }

  .icon-picker .icon-option.active {
    background: #3c8dbc;
    color: #fff;
    border-color: #367fa9;
  }
</style>

<script>
  $(document).ready(function() {
    initIconPickers();

    $('#addMasterModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var masterType = button.data('master-type');
      var masterTitle = button.data('master-title');

      $('#add_master_type').val(masterType);
      $('#add_master_name').val('');
      $('#add-master-title').text('Add ' + masterTitle);
      $('#add_master_name').attr('placeholder', 'Enter ' + masterTitle + ' Name');

      if (masterType === 'menu') {
        $('#add_menu_icon_group').show();
        setIconPickerValue('add_menu_icon', 'fa fa-folder');
      } else {
        $('#add_menu_icon_group').hide();
      }
    });

    $('#editMasterModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var masterType = button.data('master-type');
      var masterId = button.data('master-id');
      var masterName = button.data('master-name');
      var menuIcon = button.data('menu-icon') || 'fa fa-folder';
      var masterTitle = button.data('master-title');

      $('#edit_master_type').val(masterType);
      $('#edit_master_id').val(masterId);
      $('#edit_master_name').val(masterName);
      $('#edit-master-title').text('Edit ' + masterTitle);

      if (masterType === 'menu') {
        $('#edit_menu_icon_group').show();
        setIconPickerValue('edit_menu_icon', menuIcon);
      } else {
        $('#edit_menu_icon_group').hide();
      }
    });

    $('#editSubMenuModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var subMenuId = button.data('sub-menu-id');
      var menuId = button.data('menu-id');
      var subMenuName = button.data('sub-menu-name');
      var subMenuRoute = button.data('sub-menu-route');
      var subMenuIcon = button.data('sub-menu-icon');
      var sortOrder = button.data('sort-order');

      $('#edit_sub_menu_id').val(subMenuId);
      $('#edit_sub_menu_parent').val(menuId);
      $('#edit_sub_menu_name').val(subMenuName);
      $('#edit_sub_menu_route').val(subMenuRoute);
      setIconPickerValue('edit_sub_menu_icon', subMenuIcon || 'fa fa-angle-double-right');
      $('#edit_sort_order').val(sortOrder);
    });

    $(document).on('submit', '.ajax-add-form', function(event) {
      event.preventDefault();

      var form = $(this);
      var button = form.find('button[type="submit"]');
      var originalHtml = button.html();
      var data = form.serialize();

      button.prop('disabled', true);

      $.ajax({
        type: 'POST',
        url: 'class_crud_new.php?tab=<?php echo urlencode($activeTab); ?>',
        data: data,
        dataType: 'json',
        success: function(response) {
          if (response && response.status === 'success') {
            $('#addMasterModal').modal('hide');
            $('#addSubMenuModal').modal('hide');

            $('#ajax-status-message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + response.message + '</div>');

            if (form.find('input[name="master_action"]').length) {
              var masterType = form.find('input[name="master_type"]').val();
              var masterName = form.find('input[name="master_name"]').val();
              var targetTable = $('#' + masterType + '-list tbody');
              var rowCount = targetTable.find('tr').length + 1;
              var noRows = targetTable.find('tr td[colspan]').first();

              if (noRows.length) {
                noRows.closest('tr').remove();
              }

              var newRow = '';
              if (masterType === 'menu') {
                var menuIcon = response.menu_icon || 'fa fa-folder';
                var safeMenuName = $('<div/>').text(masterName).html();
                newRow = '<tr>' +
                  '<td>' + rowCount + '</td>' +
                  '<td>' + safeMenuName + '</td>' +
                  '<td><i class="' + menuIcon + '" aria-hidden="true"></i></td>' +
                  '<td><button type="button" class="btn btn-sm btn-primary open-edit-modal" data-toggle="modal" data-target="#editMasterModal" data-master-type="menu" data-master-id="' + response.master_id + '" data-master-name="' + safeMenuName + '" data-menu-icon="' + menuIcon + '" data-master-title="Menu"><i class="fa fa-pencil"></i></button></td>' +
                  
                  '</tr>';

                targetTable.append(newRow);
                appendSidebarMenu(response.master_id, masterName, response.menu_icon);

                // Keep submenu menu dropdowns in sync without refresh.
                var optionExists = $('#add_sub_menu_parent option[value="' + response.master_id + '"]').length > 0;
                if (!optionExists) {
                  var newOption = $('<option/>', {
                    value: response.master_id,
                    text: masterName
                  });
                  $('#add_sub_menu_parent').append(newOption.clone());
                  $('#edit_sub_menu_parent').append(newOption.clone());
                }
              }
            } else if (form.find('input[name="sub_menu_action"]').length) {
              var menuId = form.find('select[name="menu_id"]').val();
              var menuName = response.menu_name || '';
              var subMenuName = form.find('input[name="sub_menu_name"]').val();
              var sortOrder = response.sort_order || form.find('input[name="sort_order"]').val();
              var subMenuRoute = response.sub_menu_route || form.find('input[name="sub_menu_route"]').val() || '#';
              var subMenuIcon = response.sub_menu_icon || form.find('input[name="sub_menu_icon"]').val() || 'fa fa-angle-double-right';
              var targetTable = $('#sub-menu-list tbody');
              var rowCount = targetTable.find('tr').length + 1;
              var noRows = targetTable.find('tr td[colspan]').first();

              if (noRows.length) {
                noRows.closest('tr').remove();
              }

              var safeMenuName = $('<div/>').text(menuName).html();
              var safeSubMenuName = $('<div/>').text(subMenuName).html();
              targetTable.append(
                '<tr>' +
                '<td>' + rowCount + '</td>' +
                '<td>' + safeMenuName + '</td>' +
                '<td>' + $('<div/>').text(String(sortOrder)).html() + '</td>' +
                '<td>' + safeSubMenuName + '</td>' +
                '<td>' + $('<div/>').text(subMenuRoute).html() + '</td>' +
                '<td><i class="' + subMenuIcon + '" aria-hidden="true"></i></td>' +
                '<td><button type="button" class="btn btn-sm btn-primary open-submenu-edit-modal" data-toggle="modal" data-target="#editSubMenuModal" data-sub-menu-id="' + response.sub_menu_id + '" data-menu-id="' + menuId + '" data-sub-menu-name="' + $('<div/>').text(subMenuName).html() + '" data-sub-menu-route="' + $('<div/>').text(subMenuRoute).html() + '" data-sub-menu-icon="' + $('<div/>').text(subMenuIcon).html() + '" data-sort-order="' + sortOrder + '"><i class="fa fa-pencil"></i></button></td>' +
                '<td><form method="POST" class="ajax-delete-form" style="display:inline;" onsubmit="return confirmSubMenuDelete(' + JSON.stringify(subMenuName) + ');"><input type="hidden" name="sub_menu_action" value="delete"><input type="hidden" name="sub_menu_id" value="' + response.sub_menu_id + '"><button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></form></td>' +
                '</tr>'
              );

              appendSidebarSubMenu(menuId, menuName, response.sub_menu_id, subMenuName, response.sub_menu_route, response.sub_menu_icon, sortOrder);
            }
          } else {
            alert((response && response.message) ? response.message : 'Unable to save record.');
          }
        },
        error: function() {
          alert('Unable to save record.');
        },
        complete: function() {
          button.prop('disabled', false).html(originalHtml);
        }
      });
    });

    

    <?php if ($openAddModalType !== '') { ?>
      $('#add_master_type').val('<?php echo htmlspecialchars($openAddModalType, ENT_QUOTES); ?>');
      $('#add-master-title').text('Add <?php echo htmlspecialchars($masters[$openAddModalType]['title'], ENT_QUOTES); ?>');
      $('#add_master_name').attr('placeholder', 'Enter <?php echo htmlspecialchars($masters[$openAddModalType]['title'], ENT_QUOTES); ?> Name');
      if ('<?php echo htmlspecialchars($openAddModalType, ENT_QUOTES); ?>' === 'menu') {
        $('#add_menu_icon_group').show();
      } else {
        $('#add_menu_icon_group').hide();
      }
      $('#addMasterModal').modal('show');
    <?php } ?>

    <?php if ($openAddSubMenuModal) { ?>
      $('#addSubMenuModal').modal('show');
    <?php } ?>

  });

  function confirmMasterDelete(masterName) {
    return confirm('Delete "' + masterName + '"?');
  }

  function initIconPickers() {
    $(document).on('click', '.icon-picker .icon-option', function() {
      var button = $(this);
      var wrapper = button.closest('.icon-picker');
      var inputId = wrapper.data('target-input');
      var iconClass = button.data('icon');
      setIconPickerValue(inputId, iconClass);
    });

    $('.icon-picker').each(function() {
      var wrapper = $(this);
      var inputId = wrapper.data('target-input');
      var selectedIcon = $('#' + inputId).val();
      setIconPickerValue(inputId, selectedIcon);
    });
  }

  function setIconPickerValue(inputId, iconClass) {
    if (!iconClass) {
      return;
    }

    var input = $('#' + inputId);
    var picker = $('.icon-picker[data-target-input="' + inputId + '"]');
    var button = picker.find('.icon-option[data-icon="' + iconClass + '"]');

    if (!button.length) {
      button = picker.find('.icon-option').first();
      if (!button.length) {
        return;
      }
      iconClass = button.data('icon');
    }

    input.val(iconClass);
    picker.find('.icon-option').removeClass('active');
    button.addClass('active');
  }

  function confirmSubMenuDelete(subMenuName) {
    return confirm('Delete sub menu "' + subMenuName + '"?');
  }

  function appendSidebarMenu(menuId, menuName, menuIcon) {
    if ($('#sidebar-menu-' + menuId).length) {
      return;
    }

    var html = '';
    html += '<li class="treeview" data-menu-id="' + menuId + '" id="sidebar-menu-' + menuId + '">';
    html += '<a href="#">';
    html += '<i class="' + menuIcon + '" aria-hidden="true"></i> <span>' + menuName.toUpperCase() + '</span>';
    html += '<span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>';
    html += '</a>';
    html += '<ul class="treeview-menu" id="sidebar-submenu-' + menuId + '"></ul>';
    html += '</li>';
    $('#sidebar-dynamic-menu').append(html);
  }

  function appendSidebarSubMenu(menuId, menuName, subMenuId, subMenuName, subMenuRoute, subMenuIcon, sortOrder) {
    var target = $('#sidebar-submenu-' + menuId);
    if (!target.length) {
      appendSidebarMenu(menuId, menuName || 'Menu', 'fa fa-folder');
      target = $('#sidebar-submenu-' + menuId);
    }

    if ($('#sidebar-submenu-item-' + subMenuId).length) {
      return;
    }

    target.append('<li data-sub-menu-id="' + subMenuId + '" id="sidebar-submenu-item-' + subMenuId + '"><a href="' + subMenuRoute + '"><i class="' + subMenuIcon + '"></i>' + subMenuName.toUpperCase() + '</a></li>');
  }
</script>

<?php include "header/footer.php"; ?>