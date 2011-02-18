<html>
  <head>
  </head>
  <body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
      <table>
        <tr>
          <td align="right">MySQL user:</td>
          <td><input type="text" value="root" name="mysql_username" /></td>
        </tr>
        <tr>
          <td align="right">MySQL password:</td>
          <td><input type="text" value="" name="mysql_password" /></td>
        </tr>
        <tr>
          <td align="right">MySQL hostname:</td>
          <td><input type="text" value="localhost" name="mysql_hostname" /></td>
        </tr>
        <tr>
          <td align="right">MySQL database:</td>
          <td><input type="text" value="ccoo" name="mysql_database" /></td>
        </tr>
        <tr>
          <td align="right">ICA-AtoM table prefix:</td>
          <td><input type="text" value="q_" name="mysql_tableprefix" /></td>
        </tr>
        <tr>
          <td colspan="2" align="right"><input type="submit" value="delete"></td>
        </tr>
      </table>
    </form>

<?php
function q($m = null)
{
  die($m . '<hr /><a href='.htmlspecialchars($_SERVER['PHP_SELF']).'>Try again</a>');
}

if (0 < count($_POST))
{
  // Connect
  $conn = mysql_connect($_POST['mysql_hostname'], $_POST['mysql_username'], $_POST['mysql_password']) or q('MySQL connetion error.');

  // Select database
  mysql_select_db($_POST['mysql_database']) or q('MySQL select db error.');

  $tables = array(
    'QubitActor' => 'actor',
    'QubitDigitalObject' => 'digital_object',
    'QubitEvent' => 'event',
    'QubitFunction' => 'function',
    'QubitInformationObject' => 'information_object',
    'QubitObjectTermRelation' => 'object_term_relation',
    'QubitPhysicalObject' => 'physical_object',
    'QubitPlaceMapRelation' => 'place_map_relation',
    'QubitRelation' => 'relation',
    'QubitRepository' => 'repository',
    //'QubitSlug' => 'slug',
    'QubitStaticPage' => 'static_page',
    'QubitTaxonomy' => 'taxonomy',
    'QubitTerm' => 'term',
    'QubitUser' => 'user');

  foreach ($tables as $key => $value)
  {
    $query = "
      DELETE FROM {$_POST['mysql_tableprefix']}$value
      WHERE id NOT IN (
        SELECT id
        FROM {$_POST['mysql_tableprefix']}object
      );";

     if (!mysql_query($query))
     {
       q('MySQL Error: '.mysql_error());
     }

     echo "<p>Deleted ".mysql_affected_rows()." rows from {$_POST['mysql_tableprefix']}$value</p>";
  }

  echo '<hr /><p>Done!</p>';
} // endif

?>
  </body>
</html>
