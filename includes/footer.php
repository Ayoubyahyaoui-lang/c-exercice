    </main>
  </div>

  <aside class="bet-slip" id="betSlip">
    <div class="bet-slip-head">
      <div><strong>Bet slip</strong><span id="selectionCount">0 selections</span></div>
      <button class="icon-btn" id="clearSlip" aria-label="Clear bet slip"><i class="bi bi-trash3"></i></button>
    </div>
    <div class="selections" id="selections"></div>
    <div class="slip-empty" id="slipEmpty"><i class="bi bi-ticket-detailed"></i><p>Your bet slip is empty</p><span>Add selections to get started</span></div>
    <div class="stake-panel" id="stakePanel">
      <label for="stake">Virtual stake</label>
      <div class="stake-input"><span>₡</span><input id="stake" type="number" min="1" step="1" value="100"></div>
      <div class="total-row"><span>Total odds</span><strong id="totalOdds">1.00</strong></div>
      <div class="total-row potential"><span>Potential win</span><strong>₡ <span id="potentialWin">0.00</span></strong></div>
      <button class="btn btn-primary btn-wide" id="placeBet"><i class="bi bi-check2-circle"></i>Place virtual bet</button>
      <small>Demo mode — virtual credits only</small>
    </div>
  </aside>
</div>

<div class="auth-modal" id="authModal" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="modal-backdrop" data-close-modal></div>
  <div class="modal-card">
    <button class="modal-close" data-close-modal aria-label="Close"><i class="bi bi-x-lg"></i></button>
    <div class="auth-brand"><span class="brand-mark"><i class="bi bi-lightning-charge-fill"></i></span><strong>BETZONE216</strong></div>
    <div class="auth-tabs"><button class="active" data-auth-tab="login">Log in</button><button data-auth-tab="register">Register</button></div>
    <form id="loginForm" class="auth-form" action="auth.php" method="post">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="login">
      <label>Email<input type="email" name="email" required autocomplete="email"></label>
      <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
      <button class="btn btn-primary btn-wide" type="submit">Log in securely</button>
    </form>
    <form id="registerForm" class="auth-form hidden" action="auth.php" method="post">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="register">
      <label>Username<input type="text" name="username" minlength="3" maxlength="30" required></label>
      <label>Email<input type="email" name="email" required autocomplete="email"></label>
      <label>Password<input type="password" name="password" minlength="8" required autocomplete="new-password"></label>
      <button class="btn btn-primary btn-wide" type="submit">Create virtual account</button>
    </form>
  </div>
</div>

<div class="toast" id="toast" role="status"></div>
<script src="assets/js/app.js"></script>
</body>
</html>
