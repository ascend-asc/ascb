<?php
/**
 * admin.php — Events Admin Panel
 * ======================================
 * ASCB Website — Andres Soriano Colleges of Bislig
 *
 * Session-based password protection. All DB queries use prepared
 * statements to prevent SQL injection. All output uses htmlspecialchars().
 *
 * HOW TO USE:
 *   1. Change ADMIN_PASSWORD below to a strong password before uploading.
 *   2. Visit yoursite.com/admin.php and log in.
 *   3. Add, edit, or delete events — changes appear on the main page instantly.
 *
 * ⚠️  IMPORTANT: Do NOT upload this file without changing the password!
 */

// ─── Admin password (change before uploading to Namecheap) ────────────────
define('ADMIN_PASSWORD', 'ascb@123');   // ← CHANGE THIS!
// ──────────────────────────────────────────────────────────────────────────

require_once 'config.php';
session_start();

// Site-wide palette constants (used in inline styles below)
$NAVY = '#162659';
$GOLD = '#C9A227';
$BLUE = '#4081B2';
$BG = '#F6F8FB';

// ─── Handle Logout ─────────────────────────────────────────────────────────
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: admin.php');
  exit;
}

// ─── Handle Login ──────────────────────────────────────────────────────────
$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_password'])) {
  if ($_POST['admin_password'] === ADMIN_PASSWORD) {
    $_SESSION['admin_logged_in'] = true;
    header('Location: admin.php');
    exit;
  } else {
    $loginError = 'Incorrect password. Please try again.';
  }
}

// ─── Show login form if not authenticated ──────────────────────────────────
if (empty($_SESSION['admin_logged_in'])): ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login — ASCB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Fraunces:wght@700&display=swap"
      rel="stylesheet" />
    <style>
      *,
      *::before,
      *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      body {
        font-family: 'Inter', sans-serif;
        background: #0d1a3d;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .login-card {
        background: #fff;
        border-radius: 20px;
        padding: 48px 40px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .4);
        text-align: center;
      }

      .login-logo {
        font-family: 'Fraunces', serif;
        font-size: 1.6rem;
        color:
          <?= $NAVY ?>
        ;
        margin-bottom: 6px;
        font-weight: 700;
      }

      .login-sub {
        font-size: .82rem;
        color: #5a6a7e;
        margin-bottom: 32px;
      }

      .login-label {
        display: block;
        text-align: left;
        font-size: .8rem;
        font-weight: 600;
        color: #5a6a7e;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 6px;
      }

      .login-input {
        width: 100%;
        padding: 13px 16px;
        border-radius: 10px;
        border: 1.5px solid #d0dce9;
        font-size: .95rem;
        outline: none;
        transition: border-color .3s;
      }

      .login-input:focus {
        border-color:
          <?= $BLUE ?>
        ;
      }

      .login-btn {
        width: 100%;
        margin-top: 22px;
        padding: 14px;
        background:
          <?= $GOLD ?>
        ;
        color:
          <?= $NAVY ?>
        ;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        border-radius: 100px;
        cursor: pointer;
        transition: background .3s;
      }

      .login-btn:hover {
        background: #d9b44a;
      }

      .login-error {
        background: #fee2e2;
        color: #991b1b;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: .85rem;
        margin-top: 16px;
      }

      .back-link {
        display: inline-block;
        margin-top: 24px;
        font-size: .82rem;
        color: #5a6a7e;
        text-decoration: none;
      }

      .back-link:hover {
        color:
          <?= $NAVY ?>
        ;
      }
    </style>
  </head>

  <body>
    <div class="login-card">
      <div class="login-logo">ASCB Admin</div>
      <p class="login-sub">Andres Soriano Colleges of Bislig — Events Manager</p>
      <form method="post" action="admin.php">
        <label class="login-label" for="admin_password">Password</label>
        <input class="login-input" type="password" id="admin_password" name="admin_password"
          placeholder="Enter admin password" required autofocus />
          <?php if ($loginError): ?>
          <div class="login-error"><?= htmlspecialchars($loginError) ?></div>
          <?php endif; ?>
        <button type="submit" class="login-btn">Log In</button>
      </form>
      <a href="index.php" class="back-link">← Back to main site</a>
    </div>
  </body>

  </html>
  <?php
  exit; // Stop here — don't render admin panel for unauthenticated users
endif;

// ════════════════════════════════════════════════════════════════════════════
// AUTHENTICATED ADMIN PANEL
// ════════════════════════════════════════════════════════════════════════════

$message = '';  // Feedback message after actions

// ─── Handle DELETE ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  $deleteId = (int) $_POST['delete_id'];   // Cast to int — extra safety
  $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
  $stmt->bind_param('i', $deleteId);
  if ($stmt->execute()) {
    $message = '<div class="msg msg-success">Event deleted successfully.</div>';
  } else {
    $message = '<div class="msg msg-error">Error deleting event: ' . htmlspecialchars($conn->error) . '</div>';
  }
  $stmt->close();
}

// ─── Handle ADD or EDIT (both use POST) ────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  // Sanitize inputs
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $event_date = trim($_POST['event_date'] ?? '') ?: null;
  $image = trim($_POST['image'] ?? '') ?: null;

  if ($title === '') {
    $message = '<div class="msg msg-error">Title is required.</div>';

  } elseif ($_POST['action'] === 'add') {
    // ── INSERT new event ──
    $stmt = $conn->prepare(
      "INSERT INTO events (title, description, event_date, image) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('ssss', $title, $description, $event_date, $image);
    if ($stmt->execute()) {
      $message = '<div class="msg msg-success">Event added successfully!</div>';
    } else {
      $message = '<div class="msg msg-error">Error adding event: ' . htmlspecialchars($conn->error) . '</div>';
    }
    $stmt->close();

  } elseif ($_POST['action'] === 'edit' && !empty($_POST['event_id'])) {
    // ── UPDATE existing event ──
    $eventId = (int) $_POST['event_id'];
    $stmt = $conn->prepare(
      "UPDATE events SET title=?, description=?, event_date=?, image=? WHERE id=?"
    );
    $stmt->bind_param('ssssi', $title, $description, $event_date, $image, $eventId);
    if ($stmt->execute()) {
      $message = '<div class="msg msg-success">Event updated successfully!</div>';
    } else {
      $message = '<div class="msg msg-error">Error updating event: ' . htmlspecialchars($conn->error) . '</div>';
    }
    $stmt->close();
  }
}

// ─── Load event for editing (GET request with ?edit=ID) ────────────────────
$editEvent = null;
if (!empty($_GET['edit'])) {
  $editId = (int) $_GET['edit'];
  $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
  $stmt->bind_param('i', $editId);
  $stmt->execute();
  $result = $stmt->get_result();
  $editEvent = $result->fetch_assoc();
  $stmt->close();
}

// ─── Fetch all events for the list ─────────────────────────────────────────
$allEvents = [];
$result = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $allEvents[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Panel — ASCB Events</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Fraunces:wght@700&family=JetBrains+Mono:wght@400;600&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    /* ── Reset & Base ── */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #F6F8FB;
      color: #1e2a3a;
      line-height: 1.6;
      min-height: 100vh;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    button {
      font-family: inherit;
      cursor: pointer;
    }

    /* ── Top Bar ── */
    .topbar {
      background:
        <?= $NAVY ?>
      ;
      color: #fff;
      padding: 0 32px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 12px rgba(0, 0, 0, .3);
    }

    .topbar-brand {
      font-family: 'Fraunces', serif;
      font-size: 1.2rem;
      font-weight: 700;
    }

    .topbar-brand span {
      color:
        <?= $GOLD ?>
      ;
    }

    .topbar-actions {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .topbar-link {
      font-size: .85rem;
      color: rgba(255, 255, 255, .7);
      transition: color .2s;
    }

    .topbar-link:hover {
      color:
        <?= $GOLD ?>
      ;
    }

    .logout-btn {
      background: rgba(255, 255, 255, .1);
      border: 1px solid rgba(255, 255, 255, .2);
      color: #fff;
      border-radius: 100px;
      padding: 7px 18px;
      font-size: .85rem;
      transition: background .2s;
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, .2);
    }

    /* ── Layout ── */
    .admin-wrap {
      max-width: 1100px;
      margin: 40px auto;
      padding: 0 24px 60px;
    }

    /* ── Section headings ── */
    .section-head {
      font-family: 'Fraunces', serif;
      font-size: 1.4rem;
      color:
        <?= $NAVY ?>
      ;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #d0dce9;
    }

    /* ── Card / Panel ── */
    .panel {
      background: #fff;
      border-radius: 16px;
      padding: 36px;
      border: 1.5px solid #d0dce9;
      box-shadow: 0 4px 24px rgba(22, 38, 89, .08);
      margin-bottom: 36px;
    }

    /* ── Form ── */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-group.full {
      grid-column: 1 / -1;
    }

    .form-group label {
      font-size: .8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .05em;
      color: #5a6a7e;
    }

    .form-group input,
    .form-group textarea {
      padding: 11px 14px;
      border-radius: 10px;
      border: 1.5px solid #d0dce9;
      font-size: .93rem;
      color: #1e2a3a;
      background: #F6F8FB;
      outline: none;
      transition: border-color .3s;
      font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
      border-color:
        <?= $BLUE ?>
      ;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(64, 129, 178, .1);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 110px;
    }

    .form-hint {
      font-size: .76rem;
      color: #5a6a7e;
      margin-top: 2px;
    }

    .btn-submit {
      margin-top: 22px;
      padding: 12px 32px;
      background:
        <?= $GOLD ?>
      ;
      color:
        <?= $NAVY ?>
      ;
      font-weight: 700;
      font-size: .95rem;
      border: none;
      border-radius: 100px;
      transition: background .2s;
    }

    .btn-submit:hover {
      background: #d9b44a;
    }

    .btn-cancel {
      margin-top: 22px;
      margin-left: 12px;
      padding: 12px 24px;
      background: #E4ECF5;
      color:
        <?= $NAVY ?>
      ;
      font-weight: 600;
      font-size: .93rem;
      border: none;
      border-radius: 100px;
      transition: background .2s;
    }

    .btn-cancel:hover {
      background: #d0dce9;
    }

    /* ── Feedback messages ── */
    .msg {
      padding: 12px 18px;
      border-radius: 10px;
      font-size: .9rem;
      margin-bottom: 22px;
    }

    .msg-success {
      background: #dcfce7;
      color: #166534;
    }

    .msg-error {
      background: #fee2e2;
      color: #991b1b;
    }

    /* ── Events table ── */
    .events-table {
      width: 100%;
      border-collapse: collapse;
    }

    .events-table th {
      text-align: left;
      font-size: .76rem;
      font-weight: 700;
      letter-spacing: .06em;
      text-transform: uppercase;
      color: #5a6a7e;
      padding: 10px 14px;
      border-bottom: 2px solid #d0dce9;
    }

    .events-table td {
      padding: 14px 14px;
      font-size: .9rem;
      border-bottom: 1px solid #E4ECF5;
      vertical-align: middle;
    }

    .events-table tr:last-child td {
      border-bottom: none;
    }

    .events-table tr:hover td {
      background: #F6F8FB;
    }

    .ev-title {
      font-weight: 600;
      color:
        <?= $NAVY ?>
      ;
    }

    .ev-date {
      font-family: 'JetBrains Mono', monospace;
      font-size: .78rem;
      color:
        <?= $GOLD ?>
      ;
      white-space: nowrap;
    }

    .ev-desc {
      color: #5a6a7e;
      max-width: 320px;
    }

    .ev-img {
      font-family: 'JetBrains Mono', monospace;
      font-size: .76rem;
      color:
        <?= $BLUE ?>
      ;
    }

    /* Action buttons in table */
    .btn-edit,
    .btn-delete {
      border: none;
      border-radius: 8px;
      font-size: .8rem;
      font-weight: 600;
      padding: 6px 14px;
      transition: opacity .2s;
    }

    .btn-edit {
      background: rgba(64, 129, 178, .15);
      color:
        <?= $BLUE ?>
      ;
      margin-right: 6px;
    }

    .btn-delete {
      background: rgba(220, 38, 38, .12);
      color: #b91c1c;
    }

    .btn-edit:hover,
    .btn-delete:hover {
      opacity: .8;
    }

    /* ── No events placeholder ── */
    .no-events {
      text-align: center;
      padding: 48px;
      color: #5a6a7e;
    }

    .no-events i {
      font-size: 2.5rem;
      color: #d0dce9;
      display: block;
      margin-bottom: 12px;
    }

    /* ── Responsive ── */
    @media (max-width: 700px) {
      .form-grid {
        grid-template-columns: 1fr;
      }

      .topbar {
        padding: 0 16px;
      }

      .panel {
        padding: 22px;
      }

      .events-table th,
      .events-table td {
        padding: 10px 8px;
      }
    }
  </style>
</head>

<body>

  <!-- Top navigation bar -->
  <header class="topbar">
    <div class="topbar-brand">ASCB <span>Admin</span></div>
    <div class="topbar-actions">
      <a href="index.php" class="topbar-link" target="_blank">
        <i class="fa-solid fa-arrow-up-right-from-square"></i> View Site
      </a>
      <a href="admin.php?logout=1">
        <button class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Log Out</button>
      </a>
    </div>
  </header>

  <div class="admin-wrap">

    <!-- ── Feedback message ── -->
    <?= $message ?>

    <!-- ════════════════════════════════════════════
         SECTION 1: ADD / EDIT EVENT FORM
         ════════════════════════════════════════════ -->
    <div class="panel">
      <h2 class="section-head">
        <?= $editEvent ? '<i class="fa-solid fa-pen-to-square"></i> Edit Event' : '<i class="fa-solid fa-circle-plus"></i> Add New Event' ?>
      </h2>
      <form method="post" action="admin.php">
        <!-- Hidden fields for edit mode -->
        <input type="hidden" name="action" value="<?= $editEvent ? 'edit' : 'add' ?>" />
        <?php if ($editEvent): ?>
          <input type="hidden" name="event_id" value="<?= (int) $editEvent['id'] ?>" />
        <?php endif; ?>

        <div class="form-grid">
          <!-- Title (required) -->
          <div class="form-group full">
            <label for="title">Event Title <span style="color:#b91c1c">*</span></label>
            <input type="text" id="title" name="title" placeholder="e.g. ASCB Founding Anniversary 2025"
              value="<?= htmlspecialchars($editEvent['title'] ?? '') ?>" required />
          </div>

          <!-- Date -->
          <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" id="event_date" name="event_date"
              value="<?= htmlspecialchars($editEvent['event_date'] ?? '') ?>" />
          </div>

          <!-- Image -->
          <div class="form-group">
            <label for="image">Image Filename</label>
            <input type="text" id="image" name="image" placeholder="e.g. event-photo.jpg"
              value="<?= htmlspecialchars($editEvent['image'] ?? '') ?>" />
            <span class="form-hint">
              Filename only (no path). Upload the image to the <code>img/</code> folder first.
            </span>
          </div>

          <!-- Description -->
          <div class="form-group full">
            <label for="description">Description</label>
            <textarea id="description" name="description"
              placeholder="Write event details here…"><?= htmlspecialchars($editEvent['description'] ?? '') ?></textarea>
          </div>
        </div>

        <button type="submit" class="btn-submit">
          <?= $editEvent ? '<i class="fa-solid fa-floppy-disk"></i> Save Changes' : '<i class="fa-solid fa-plus"></i> Add Event' ?>
        </button>
        <?php if ($editEvent): ?>
          <a href="admin.php"><button type="button" class="btn-cancel">Cancel</button></a>
        <?php endif; ?>
      </form>
    </div><!-- /.panel -->

    <!-- ════════════════════════════════════════════
         SECTION 2: EXISTING EVENTS LIST
         ════════════════════════════════════════════ -->
    <div class="panel">
      <h2 class="section-head"><i class="fa-solid fa-list"></i> All Events</h2>

      <?php if (empty($allEvents)): ?>
        <div class="no-events">
          <i class="fa-solid fa-calendar-xmark"></i>
          <p>No events yet. Use the form above to add your first event.</p>
        </div>

      <?php else: ?>
        <div style="overflow-x:auto;">
          <table class="events-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Date</th>
                <th>Image</th>
                <th>Description</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allEvents as $ev): ?>
                <tr>
                  <td><?= (int) $ev['id'] ?></td>
                  <td class="ev-title"><?= htmlspecialchars($ev['title']) ?></td>
                  <td class="ev-date">
                      <?= $ev['event_date'] ? htmlspecialchars(date('M d, Y', strtotime($ev['event_date']))) : '—' ?>
                  </td>
                  <td class="ev-img">
                      <?= $ev['image'] ? htmlspecialchars($ev['image']) : '<span style="color:#d0dce9">none</span>' ?>
                  </td>
                  <td class="ev-desc">
                      <?= htmlspecialchars(mb_strimwidth($ev['description'] ?? '', 0, 100, '…')) ?>
                  </td>
                  <td style="white-space:nowrap;">
                    <!-- Edit button — goes back to the same page with ?edit=ID -->
                    <a href="admin.php?edit=<?= (int) $ev['id'] ?>">
                      <button class="btn-edit"><i class="fa-solid fa-pen"></i> Edit</button>
                    </a>
                    <!-- Delete button — submits a POST form for the delete handler -->
                    <form method="post" action="admin.php" style="display:inline;"
                      onsubmit="return confirm('Delete this event? This cannot be undone.');">
                      <input type="hidden" name="delete_id" value="<?= (int) $ev['id'] ?>" />
                      <button type="submit" class="btn-delete">
                        <i class="fa-solid fa-trash"></i> Delete
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

    </div><!-- /.panel -->

  </div><!-- /.admin-wrap -->

</body>

</html>
<?php
$conn->close();
?>