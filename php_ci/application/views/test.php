<div id="body">
	<h1>Home</h1>
	<code>
		<form>
			<div>
				<h3>Choose Continent</h3>
				<ul>
					<?php
					foreach($continent_list as $row) {
						echo "<li>";
						echo "<input type='radio' name='continent' value='" . $row->Continent . "' " . ($continent == $row->Continent ? "checked='checked'" : "") . ">";
						echo $row->Continent;
						echo "</li>";
					}
					?>
				</ul>
			</div>
			<div>
				country: <input type="text" id="country" name="country"
					value="<?php echo $country;?>" /> <input type="submit"
					value="input" />
			</div>
		</form>

		<?php
		if(count($list) > 0) {
		?>
		<p>


		<h3>Information</h3>
		<table>
			<?php
			$idx = 0;
			foreach($list as $rows) {
					echo "<tr>";
					$keys = array_keys((array) $rows);

					if($idx == 0) {
						foreach($keys as $key) {
							echo "<th>" . $key . "</th>";
						}

						echo "</tr><tr>";
					}

					$idx++;

					foreach($rows as $key => $value) {
						echo "<td>" . $value . "</td>";
					}

					echo "</tr>";
				}
				?>
		</table>
		</p>
		<?php
			}
			?>
	</code>
</div>

