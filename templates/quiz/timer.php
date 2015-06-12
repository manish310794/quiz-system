<script>
			var time = 60; /* how long the timer runs for */
			var initialOffset = '440';
			var i = 1
			var interval = setInterval(function() {
				$('.circle_animation').css('stroke-dashoffset', initialOffset-(i*(initialOffset/time)));
				$('h2').text(i);
				if (i == time) {
					clearInterval(interval);
				}
				i++;  
			}, 1000);
		</script>
		<div class="item html">
			<h2>0</h2>
			<svg width="160" height="160" xmlns="http://www.w3.org/2000/svg">
			 <g>
			  <title>Layer 1</title>
			  <circle id="circle" class="circle_animation" r="69.85699" cy="81" cx="81" stroke-width="8" stroke="#6fdb6f" fill="none"/>
			 </g>
			</svg>
		</div>s