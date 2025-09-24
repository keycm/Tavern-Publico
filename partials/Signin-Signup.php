 <div id="signInUpModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>

            <div id="signInPanel" class="modal-panel active">
                <h2 class="modal-title">Sign In</h2>
                <form id="signInForm" class="modal-form">
                    <div class="form-group">
                        <label for="loginUsernameEmail">Username or Email</label>
                        <input type="text" id="loginUsernameEmail" name="username_email"
                            placeholder="Enter your username or email" required>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" name="password" placeholder="Enter your password"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary modal-btn">Sign In</button>
                </form>
                <p class="modal-bottom-text">Don't have an account? <a href="#" class="switch-to-register">Register
                        here</a></p>
            </div>

            <div id="registerPanel" class="modal-panel">
                <h2 class="modal-title">Register</h2>
                <form id="registerForm" class="modal-form">
                    <div class="form-group">
                        <label for="registerName">Username</label>
                        <input type="text" id="registerName" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="registerEmail">Email</label>
                        <input type="email" id="registerEmail" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="registerPassword">Password</label>
                        <input type="password" id="registerPassword" name="password" placeholder="Create a password"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary modal-btn">Register</button>
                </form>
                <p class="modal-bottom-text">Already have an account? <a href="#" class="switch-to-signin">Sign In
                        here</a></p>
            </div>
        </div>
    </div>