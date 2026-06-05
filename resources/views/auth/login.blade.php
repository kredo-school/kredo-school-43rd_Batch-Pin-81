<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pin+81 - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      /* 背景の淡いグラデーションを再現 */
      background: linear-gradient(135deg, #f3f5f9 0%, #f7e9e9 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      font-family: 'Helvetica Neue', Arial, sans-serif;
    }
    .login-card {
      background-color: #ffffff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      width: 100%;
      max-width: 440px;
      padding: 2.5rem;
    }
    /* ボタンのカスタムカラー */
    .btn-dark-navy {
      background-color: #0d233a;
      color: #ffffff;
      border: none;
    }
    .btn-dark-navy:hover {
      background-color: #071424;
      color: #ffffff;
    }
    .btn-guest {
      background-color: #fdf3e7;
      color: #7c5329;
      border: none;
    }
    .btn-guest:hover {
      background-color: #fae6cf;
      color: #7c5329;
    }
    /* リンクの微調整 */
    .custom-link {
      color: #0d233a;
      text-decoration: none;
      font-weight: 500;
    }
    .custom-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle border mb-2" style="width: 70px; height: 70px; font-size: 1.2rem; font-weight: bold; color: #0d233a;">
      Pin+81
    </div>
    <h2 class="fw-bold m-0" style="color: #0d233a;">Pin+81</h2>
    <p class="text-muted small mt-2">Welcome back! Please login to your account.</p>
  </div>

  <div class="login-card">
    <h4 class="fw-bold mb-1" style="color: #212529;">Login</h4>
    <p class="text-muted small mb-4">Access your account to make reservations or manage your restaurant</p>

    <form>
      <div class="mb-3">
        <label for="email" class="form-label small fw-bold text-secondary m-1">Email</label>
        <input type="email" class="form-control form-control-lg fs-6" id="email" placeholder="your@email.com" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label small fw-bold text-secondary m-1">Password