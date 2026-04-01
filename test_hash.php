<?php
echo "Hash for 'admin': " . password_hash('admin', PASSWORD_DEFAULT) . "\n";
echo "Hash for 'password': " . password_hash('password', PASSWORD_DEFAULT) . "\n";
echo "Hash for '123456': " . password_hash('123456', PASSWORD_DEFAULT) . "\n";
echo "Hash for 'godzilla': " . password_hash('godzilla', PASSWORD_DEFAULT) . "\n";
