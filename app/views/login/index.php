	<?php require_once 'app/views/templates/headerPublic.php'?>
	<main role="main" class="container">
			<div class="page-header" id="banner">
					<div class="row">
							<div class="col-lg-12">
									<h1>You are not logged in</h1>
							</div>
					</div>
			</div>
			<?php if (!empty($data['fail'])): ?>
					<div class="alert alert-danger">Login failed. Please try again.</div>
			<?php endif; ?>
			<?php if (!empty($data['locked'])): ?>
					<div class="alert alert-warning">Too many failed attempts. Please wait 60 seconds before trying again.</div>
			<?php endif; ?>
			<div class="row">
					<div class="col-sm-auto">
							<form action="/login/verify" method="post">
									<fieldset>
											<div class="form-group">
													<label for="username">Username</label>
													<input required type="text" class="form-control" name="username">
											</div>
											<div class="form-group">
													<label for="password">Password</label>
													<input required type="password" class="form-control" name="password">
											</div>
											<br>
											<button type="submit" class="btn btn-primary">Login</button>
									</fieldset>
							</form>
							<p>Don't have an account? <a href="/create">Create one</a></p>
					</div>
			</div>
	<?php require_once 'app/views/templates/footer.php' ?>
