<?php
// Detect if we're in a subdirectory (like admin/) or root
$imagePath = (basename(dirname($_SERVER['PHP_SELF'])) === 'admin') ? '../image/' : 'image/';
?>

	<div class="footerMain">
		<div class="content">
			<div class="footer-grids">
				<div class="footer one">
					<h3>More About Us</h3>
					<p>Edu Bridge is an online private tutor platform designed to connect students and parents with qualified teachers. It aims to make finding and hiring tutors easier, faster, and more reliable.</p>
                       <p class="adam">– Atqiya Anjum</p>
					<div class="clear"></div>
				</div>
				<div class="footer two">
					<h3>Keep Connected</h3>
					<ul>
						<li><a class="fb" href="https://www.facebook.com/EduBridge/"><i></i>Like us on Facebook</a></li>
						<li><a class="fb1" href="https://www.youtube.com/EduBridge"><i></i>Follow us on Twitter</a></li>
					</ul>
				</div>
				<div class="footer three">
					<h3>Contact Information</h3>
					<ul>
						<li><span class="c-icon"><img src="<?php echo $imagePath; ?>image1.png" alt="Phone"></span><span class="c-text">+8801234567890</span></li>
						<li><span class="c-icon"><img src="<?php echo $imagePath; ?>image2.png" alt="Email"></span><span class="c-text">atqiyaanju@gmail.com</span></li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
