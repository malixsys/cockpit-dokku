<?php


$configs = [];

if (!empty(getenv('COCKPIT_SESSION_NAME'))){
  $configs['session.name'] = getenv('COCKPIT_SESSION_NAME');
}

if (!empty(getenv('COCKPIT_SALT'))){
  $configs['sec-key'] = getenv('COCKPIT_SALT');
}

if (!empty(getenv('COCKPIT_I18N'))){
  $configs['i18n'] = getenv('COCKPIT_I18N');
}

if (!empty(getenv('MONGO_URL'))){
  $configs['database'] = [
    "server"  => getenv('MONGO_URL'),
    "options" => ["db" => substr(getenv('MONGO_URL'), strrpos(getenv('MONGO_URL'), '/') + 1)]
  ];
}

if (!empty(getenv('COCKPIT_MAILER_FROM'))){
  $configs['mailer'] = [
      "from"      => getenv('COCKPIT_MAILER_FROM'),
      "transport" => getenv('COCKPIT_MAILER_TRANSPORT'),
      "host"      => getenv('COCKPIT_MAILER_HOST'),
      "user"      => getenv('COCKPIT_MAILER_USER'),
      "password"  => getenv('COCKPIT_MAILER_PASSWORD'),
      "port"      => getenv('COCKPIT_MAILER_PORT'),
      "auth"      => getenv('COCKPIT_MAILER_AUTH'),
      "encryption"=> getenv('COCKPIT_MAILER_ENCRYPTION')
  ];
}
return $configs;
