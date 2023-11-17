<?php
($_POST);
$_ENV=parse_ini_file('.env');
 $mysqli = mysqli_init();
 $mysqli->ssl_set(NULL, NULL, "./cacert.pem", NULL, NULL);
 $mysqli->real_connect($_ENV["HOST"], $_ENV["USERNAME"], $_ENV["PASSWORD"], $_ENV["DATABASE"]);
 include("./Funciones.php");
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Nombre de la pastilla: <br>
    <input type="text" name="Nompas" id="Nombre" ><br>
    Breve descripción: <br>
    <input type ="text" name="Desc"id="descripcion"><br>
    Enfermedadades que cubre: <br>
    <input type="text" name="patologias"id="Patologia"><br>
    Día y hora de inicio: 
    <input type="date-time" name="momento"id="inicio"><br>
    Día y hora de finalización: 
    <input type="date-time" name="momentof"id="fin"><br>
    tubo:
    <input type="text" name="tubo"id="tubo"><br>
    
    <button id="authorize_button" for onclick="handleAuthClick()">Authorize</button>
    <button id="signout_button" onclick="handleSignoutClick()">Sign Out</button>
    <button id="create-button" onclick="handleCreateEvent()">Create</button>

    <pre id="content" style="white-space: pre-wrap;"></pre>
    <script>
    /* exported gapiLoaded */
    /* exported gisLoaded */
    /* exported handleAuthClick */
    /* exported handleSignoutClick */
    
    // TODO(developer): Set to client ID and API key from the Developer Console
    const CLIENT_ID = "127642041900-vnml5vf8dh95563pe3dqmo0c9bq3bfdf.apps.googleusercontent.com";
    const API_KEY = "AIzaSyCuj8M8oFCV148zGDDeT_we3lEsUCU44SM";

    // Discovery doc URL for APIs used by the quickstart
    const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest';

    // Authorization scopes required by the API; multiple scopes can be
    // included, separated by spaces.
    const SCOPES = 'https://www.googleapis.com/auth/calendar.readonly';

    let tokenClient;
    let gapiInited = false;
    let gisInited = false;

    document.getElementById('authorize_button').style.visibility = 'hidden';
    document.getElementById('signout_button').style.visibility = 'hidden';
    document.getElementById('create-button').style.visibility = 'hidden';

    /**
     * Callback after api.js is loaded.
     */
    function gapiLoaded() {
      gapi.load('client', initializeGapiClient);
    }
    /**
     * Callback after the API client is loaded. Loads the
     * discovery doc to initialize the API.
     */
    async function initializeGapiClient() {
      await gapi.client.init({
        apiKey: API_KEY,
        discoveryDocs: [DISCOVERY_DOC],
      });
      gapiInited = true;
      maybeEnableButtons();
    }

    /**
     * Callback after Google Identity Services are loaded.
     */
    function gisLoaded() {
      tokenClient = google.accounts.oauth2.initTokenClient({
        client_id: CLIENT_ID,
        scope: SCOPES,
        callback: '', // defined later
      });
      gisInited = true;
      maybeEnableButtons();
    }

    /**
     * Enables user interaction after all libraries are loaded.
     */
    function maybeEnableButtons() {
      if (gapiInited && gisInited) {
        document.getElementById('authorize_button').style.visibility = 'visible';
      }
    }

    /**
     *  Sign in the user upon button click.
     */
    function handleAuthClick() {
      tokenClient.callback = async (resp) => {
        if (resp.error !== undefined) {
          throw (resp);
        }
        document.getElementById('signout_button').style.visibility = 'visible';
        document.getElementById('authorize_button').innerText = 'Refresh';
        document.getElementById('create-button').style.visibility = 'visible';
        await listUpcomingEvents();
      };

      if (gapi.client.getToken() === null) {
        // Prompt the user to select a Google Account and ask for consent to share their data
        // when establishing a new session.
        tokenClient.requestAccessToken({ prompt: 'consent' });
      } else {
        // Skip display of account chooser and consent dialog for an existing session.
        tokenClient.requestAccessToken({ prompt: '' });
      }
    }

    /**
     *  Sign out the user upon button click.
     */
    function handleSignoutClick() {
      const token = gapi.client.getToken();
      if (token !== null) {
        google.accounts.oauth2.revoke(token.access_token);
        gapi.client.setToken('');
        document.getElementById('content').innerText = '';
        document.getElementById('authorize_button').innerText = 'Authorize';
        document.getElementById('signout_button').style.visibility = 'hidden';
        document.getElementById('create-button').style.visibility = 'hidden';
      }
    }

    function handleCreateEvent(e) {
      const desc = document.getElementById("descripcion").value;
      const nombre = document.getElementById("Nombre").value;
      const pato = document.getElementById("Patologia").value;
      const inicio = document.getElementById("inicio").value;
      const fin = document.getElementById("fin").value;
      const tubo = document.getElementById("tubo").value;

      const inicio_fecha = inicio.split(" ")[0];
      const inicio_hora = inicio.split(" ")[1];
      const fin_fecha = fin.split(" ")[0];
      const fin_hora = fin.split(" ")[1];
      
      const event = {
        summary: tubo,
        location: '800 Howard St., San Francisco, CA 94103',
        description: `${desc}, ${nombre}, ${pato}`,
        start: {
          dateTime: `${inicio_fecha.split("/")[2]}-${inicio_fecha.split("/")[1]}-${inicio_fecha.split("/")[0]}T${inicio_hora.split(":")[0]}:${inicio_hora.split(":")[1]}:00-03:00`,
          timeZone: 'America/Buenos_Aires'
        },
        end: {
          dateTime: `${fin_fecha.split("/")[2]}-${fin_fecha.split("/")[1]}-${fin_fecha.split("/")[0]}T${fin_hora.split(":")[0]}:${fin_hora.split(":")[1]}:00-03:00`,
          timeZone: 'America/Buenos_Aires'
        },
        recurrence: [
          'RRULE:FREQ=DAILY;COUNT=2'
        ],
        attendees: [
          { email: 'lpage@example.com' },
          { email: 'sbrin@example.com' }
        ],
        reminders: {
          useDefault: false,
          overrides: [
            { method: 'email', minutes: 24 * 60 },
            { method: 'popup', minutes: 10 }
          ]
        }
      };

      const request = gapi.client.calendar.events.insert({
        'calendarId': 'primary',
        'resource': event
      }).then(() => {
        handleAuthClick();
      });
    }

    /**
     * Print the summary and start datetime/date of the next ten events in
     * the authorized user's calendar. If no events are found an
     * appropriate message is printed.
     */
    async function listUpcomingEvents() {
      let response;
      try {
        const request = {
          'calendarId': 'primary',
          'timeMin': (new Date()).toISOString(),
          'showDeleted': false,
          'singleEvents': true,
          'maxResults': 10,
          'orderBy': 'startTime',
        };
        response = await gapi.client.calendar.events.list(request);
      } catch (err) {
        document.getElementById('content').innerText = err.message;
        return;
      }

      const events = response.result.items;
      if (!events || events.length == 0) {
        document.getElementById('content').innerText = 'No events found.';
        return;
      }
      // Flatten to string to display
      const output = events.reduce(
        (str, event) => `${str}${event.summary} (${event.start.dateTime || event.start.date})\n`,
        'Events:\n');
      document.getElementById('content').innerText = output;
    }
  </script>
  <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
  <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
</body>

</html>
    
    <?php
    /*$botonagregar="";
    if (isset ($_POST["boton5"])){
        $botonagregar="boton5";
    }
    if ($botonagregar){
    
    $Nombrepast=$_POST["Nompas"];
    $Desc=$_POST["Desc"];
    $patologias=$_POST["patologias"];
    $Diayhora=$_POST["momento"];
    $tubo=$_POST["tubo"];
    $veces="";
    $idhorarios=count("Pastillas")+1;
    if (empty($Nombrepast)|| empty( $Desc) ||empty($patologias)||empty($Diayhora)){
        echo 'Faltan datos';
        exit();
      }
      else if (!isset($Nombrepast, $Desc, $patologias, $Diayhora)){
        echo 'Faltan datos';
        exit();
      }
    else{
        $query_insertar_pastilla= "INSERT INTO  Pastillas  (PastillaNombre, PastillaDescripcion, Tubo, PastillaEnf) VALUES (?,?,?,?)";
        $stmt_insertar_pastilla=$mysqli->prepare ($query_insertar_pastilla);
        $stmt_insertar_pastilla->bind_param("ssis", $Nombrepast,$Desc,$tubo,$patologias);
        if($stmt_insertar_pastilla->execute()){
          $stmt_insertar_pastilla->store_result();
          echo"listo";
        }
        else{
          echo "No se pudo ejecutar";
        }
        if ($_POST["select"]== "1vez"){
          $veces=1;
        }
        else if ($_POST["select"]== "4veces"){
          $veces=4;
        }
          $query_insertar_horario="INSERT INTO Horarios (Horarios, Horariosveces, Horarios_pastillas) VALUES (?,?,?)";
          $stmt_insertar_horario=$mysqli->prepare ($query_insertar_horario);
          $stmt_insertar_horario ->bind_param("dii", $Diayhora,$veces,$idhorarios);
          if($stmt_insertar_horario->execute()){
            $stmt_insertar_horario->store_result();
            echo"listo";
          }
          else{
            echo "No se pudo ejecutar";
          }   
    }
    }*/
    ?>