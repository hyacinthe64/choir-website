<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_login();

$pageTitle = 'Manage Songs';
$activeNav = 'songs';
$error = '';

// Upload
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='upload') {
    $title    = trim($_POST['title']    ?? '');
    $composer = trim($_POST['composer'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $lyrics   = trim($_POST['lyrics']   ?? '');

    if ($title==='' || empty($_FILES['song_file']['name'])) {
        $error = 'Please provide a song title and choose an audio file.';
    } else {
        $ext = strtolower(pathinfo($_FILES['song_file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext,['mp3','wav','ogg','m4a'])) {
            $error = 'Unsupported file type. Allowed: mp3, wav, ogg, m4a';
        } else {
            $fileName = 'song_'.time().'_'.rand(100,999).'.'.$ext;
            move_uploaded_file($_FILES['song_file']['tmp_name'], '../uploads/songs/'.$fileName);
            $pdo->prepare("INSERT INTO songs (title,composer,category,file_path,lyrics) VALUES (?,?,?,?,?)")
                ->execute([$title,$composer,$category,$fileName,$lyrics]);
            header('Location: songs_admin.php?uploaded=1');
            exit;
        }
    }
}

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $s  = $pdo->prepare("SELECT file_path FROM songs WHERE id=?");
    $s->execute([$id]);
    $song = $s->fetch();
    if ($song) {
        $fp = '../uploads/songs/'.$song['file_path'];
        if (file_exists($fp)) unlink($fp);
        $pdo->prepare("DELETE FROM songs WHERE id=?")->execute([$id]);
    }
    header('Location: songs_admin.php?deleted=1');
    exit;
}

$songs = $pdo->query("SELECT * FROM songs ORDER BY uploaded_at DESC")->fetchAll();

require '_sidebar.php';
?>

<div class="admin-topbar"><h1>Manage Songs</h1></div>

<?php if (isset($_GET['uploaded'])): ?>
  <div class="alert alert-success">🎵 Song uploaded! It is now live on the public Songs page.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
  <div class="alert alert-success">Song deleted successfully.</div>
<?php endif; ?>

<div class="grid grid-2">

  <!-- UPLOAD FORM -->
  <div class="card">
    <h3>Upload New Song</h3>
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="action" value="upload">
      <div class="form-group">
        <label>Song Title *</label>
        <input type="text" name="title" required>
      </div>
      <div class="form-group">
        <label>Composer / Author</label>
        <input type="text" name="composer">
      </div>
      <div class="form-group">
        <label>Category</label>
        <input type="text" name="category" placeholder="e.g. Worship, Praise, Hymn">
      </div>
      <div class="form-group">
        <label>Audio File * (mp3, wav, ogg, m4a)</label>
        <input type="file" name="song_file" accept="audio/*" required>
      </div>
      <div class="form-group">
        <label>Lyrics (optional)</label>
        <textarea name="lyrics" rows="5" placeholder="Paste lyrics here..."></textarea>
      </div>
      <button type="submit" class="btn btn-primary">⬆ Upload Song</button>
    </form>
  </div>

  <!-- SONG LIST -->
  <div class="card">
    <h3>Uploaded Songs (<?= count($songs) ?>)</h3>
    <?php if (empty($songs)): ?>
      <p class="text-muted">No songs uploaded yet.</p>
    <?php else: ?>
      <div style="max-height:520px;overflow-y:auto;">
        <?php foreach ($songs as $song): ?>
          <div style="border-bottom:1px solid #eee;padding:12px 0;">
            <div class="flex-between" style="margin-bottom:6px;">
              <div>
                <strong><?= htmlspecialchars($song['title']) ?></strong>
                <div class="text-muted" style="font-size:13px;">
                  <?= $song['composer'] ? htmlspecialchars($song['composer']).' &middot; ':'' ?>
                  <?= date('d M Y',strtotime($song['uploaded_at'])) ?>
                  <?php if ($song['category']): ?>
                    &middot; <span class="tag"><?= htmlspecialchars($song['category']) ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <a href="songs_admin.php?delete=<?= $song['id'] ?>" class="btn btn-danger btn-sm"
                 onclick="return confirm('Permanently delete this song?');">Delete</a>
            </div>
            <audio controls style="width:100%;">
              <source src="../uploads/songs/<?= htmlspecialchars($song['file_path']) ?>">
            </audio>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require '_footer.php'; ?>
