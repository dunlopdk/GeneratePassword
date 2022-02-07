# GeneratePassword

GeneratePassword is a PHP class for generating random passwords.

## Installation

Simply copy the GeneratePassword;.php into a folder of your choice and include it in your script.
Please note, this script assumes you have installed the mbstring extension.
If this is not the case or the php version is lower than 7.4, please make use of the [symfony/polyfill-mbstring](https://github.com/symfony/polyfill-mbstring)

## Usage

```php
require_once('GeneratePassword.php');

#defaults to a mixed password 8 characters long 
$myGeneratePassword = new GeneratePassword();
$myGeneratePassword->generatePassword();

# returns a password 15 characters long
$myGeneratePassword = new GeneratePassword(15);
$myGeneratePassword->generatePassword();

# For a more custom render, you have the options of setting different amount of each type
# Note: if your specifications do not match the length you provided, the code will throw and exception and exit.
# valid fields: lowercaseLength, uppercaseLength, numericLength, specialCharsLength and otherCharsLength
# to turn off a character type, simply set the value to 0
$options = array('lowercaseLength' => 2, 'specialCharsLength' => 8);
$myGeneratePassword = new GeneratePassword(10, $options);
$myGeneratePassword->generatePassword();

# You can even add more characters to the mix, just add them to the array you provide in the field otherChars
$options = array('lowercaseLength' => 6, 'otherChars' => "üßæøæ", 'otherCharsLength' => 2);
$myGeneratePassword = new GeneratePassword(8, $options);
$myGeneratePassword->generatePassword();
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
