<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 24px; color: #222; }
    .card { background: #fff; border-radius: 10px; padding: 32px; max-width: 560px; margin: 0 auto; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .header { border-bottom: 3px solid #005BAA; padding-bottom: 16px; margin-bottom: 24px; }
    .header h2 { margin: 0; color: #005BAA; font-size: 18px; }
    .row { display: flex; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
    .label { font-weight: bold; width: 100px; flex-shrink: 0; color: #555; font-size: 13px; }
    .value { font-size: 13px; color: #222; }
    .message-box { background: #f9f9f9; border-left: 3px solid #005BAA; padding: 12px 16px; margin-top: 20px; border-radius: 4px; font-size: 13px; line-height: 1.7; white-space: pre-wrap; }
    .footer { margin-top: 24px; font-size: 11px; color: #aaa; text-align: center; }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <h2>Pesan Baru dari Form Kontak</h2>
    </div>
    <div class="row"><span class="label">Nama</span><span class="value">{{ $contact->name }}</span></div>
    <div class="row"><span class="label">Email</span><span class="value">{{ $contact->email }}</span></div>
    <div class="row"><span class="label">Telepon</span><span class="value">{{ $contact->phone ?: '-' }}</span></div>
    <div class="row"><span class="label">Subjek</span><span class="value">{{ $contact->subject }}</span></div>
    <div class="message-box">{{ $contact->message }}</div>
    <div class="footer">Dikirim melalui form kontak website Jatiwangi Art Factory</div>
  </div>
</body>
</html>
