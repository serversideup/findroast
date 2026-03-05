const countries = [
	{ 
		abbr: 'AF', 
		name: 'Afghanistan',
		flag: '🇦🇫'
	},
	{ 
		abbr: 'AX', 
		name: 'Åland Islands',
		flag: '🇦🇽'
	},
	{ 
		abbr: 'AL', 
		name: 'Albania',
		flag: '🇦🇱'
	},
	{ 
		abbr: 'DZ', 
		name: 'Algeria',
		flag: '🇩🇿'
	},
	{ 
		abbr: 'AS', 
		name: 'American Samoa',
		flag: '🇦🇸'
	},
	{ 
		abbr: 'AD', 
		name: 'Andorra',
		flag: '🇦🇩'
	},
	{ 
		abbr: 'AO', 
		name: 'Angola',
		flag: '🇦🇴'
	},
	{ 
		abbr: 'AI', 
		name: 'Anguilla',
		flag: '🇦🇮'
	},
	{ 
		abbr: 'AQ', 
		name: 'Antarctica',
		flag: '🇦🇶'
	},
	{ 
		abbr: 'AG', 
		name: 'Antigua and Barbuda',
		flag: '🇦🇬'
	},
	{ 
		abbr: 'AR', 
		name: 'Argentina',
		flag: '🇦🇷'
	},
	{ 
		abbr: 'AM', 
		name: 'Armenia',
		flag: '🇦🇲'
	},
	{ 
		abbr: 'AW', 
		name: 'Aruba',
		flag: '🇦🇼'
	},
	{ 
		abbr: 'AU', 
		name: 'Australia',
		flag: '🇦🇺'
	},
	{ 
		abbr: 'AT', 
		name: 'Austria',
		flag: '🇦🇹'
	},
	{ 
		abbr: 'AZ', 
		name: 'Azerbaijan',
		flag: '🇦🇿'
	},
	{ 
		abbr: 'BS', 
		name: 'Bahamas',
		flag: '🇧🇸'
	},
	{ 
		abbr: 'BH', 
		name: 'Bahrain',
		flag: '🇧🇭'
	},
	{ 
		abbr: 'BD', 
		name: 'Bangladesh',
		flag: '🇧🇩'
	},
	{ 
		abbr: 'BB', 
		name: 'Barbados',
		flag: '🇧🇧'
	},
	{ 
		abbr: 'BY', 
		name: 'Belarus',
		flag: '🇧🇾'
	},
	{ 
		abbr: 'BE', 
		name: 'Belgium',
		flag: '🇧🇪'
	},
	{ 
		abbr: 'BZ', 
		name: 'Belize',
		flag: '🇧🇿'
	},
	{ 
		abbr: 'BJ', 
		name: 'Benin',
		flag: '🇧🇯'
	},
	{ 
		abbr: 'BM', 
		name: 'Bermuda',
		flag: '🇧🇲'
	},
	{ 
		abbr: 'BT', 
		name: 'Bhutan',
		flag: '🇧🇹'
	},
	{ 
		abbr: 'BO', 
		name: 'Bolivia',
		flag: '🇧🇴'
	},
	{ 
		abbr: 'BQ', 
		name: 'Bonaire, Sint Eustatius and Saba',
		flag: '🇧🇶'
	},
	{ 
		abbr: 'BA', 
		name: 'Bosnia and Herzegovina',
		flag: '🇧🇦'
	},
	{ 
		abbr: 'BW', 
		name: 'Botswana',
		flag: '🇧🇼'
	},
	{ 
		abbr: 'BV', 
		name: 'Bouvet Island',
		flag: '🇧🇻'
	},
	{ 
		abbr: 'BR', 
		name: 'Brazil',
		flag: '🇧🇷'
	},
	{ 
		abbr: 'IO', 
		name: 'British Indian Ocean Territory',
		flag: '🇮🇴'
	},
	{ 
		abbr: 'BN', 
		name: 'Brunei Darussalam',
		flag: '🇧🇳'
	},
	{ 
		abbr: 'BG', 
		name: 'Bulgaria',
		flag: '🇧🇬'
	},
	{ 
		abbr: 'BF', 
		name: 'Burkina Faso',
		flag: '🇧🇫'
	},
	{ 
		abbr: 'BI', 
		name: 'Burundi',
		flag: '🇧🇮'
	},
	{ 
		abbr: 'KH', 
		name: 'Cambodia',
		flag: '🇰🇭'
	},
	{ 
		abbr: 'CM', 
		name: 'Cameroon',
		flag: '🇨🇲'
	},
	{ 
		abbr: 'CA', 
		name: 'Canada',
		flag: '🇨🇦'
	},
	{ 
		abbr: 'CV', 
		name: 'Cape Verde',
		flag: '🇨🇻'
	},
	{ 
		abbr: 'KY', 
		name: 'Cayman Islands',
		flag: '🇰🇾'
	},
	{ 
		abbr: 'CF', 
		name: 'Central African Republic',
		flag: '🇨🇫'
	},
	{ 
		abbr: 'TD', 
		name: 'Chad',
		flag: '🇹🇩'
	},
	{ 
		abbr: 'CL', 
		name: 'Chile',
		flag: '🇨🇱'
	},
	{ 
		abbr: 'CN', 
		name: 'China',
		flag: '🇨🇳'
	},
	{ 
		abbr: 'CX', 
		name: 'Christmas Island',
		flag: '🇨🇽'
	},
	{ 
		abbr: 'CC', 
		name: 'Cocos (Keeling) Islands',
		flag: '🇨🇨'
	},
	{ 
		abbr: 'CO', 
		name: 'Colombia',
		flag: '🇨🇴'
	},
	{ 
		abbr: 'KM', 
		name: 'Comoros',
		flag: '🇰🇲'
	},
	{ 
		abbr: 'CG', 
		name: 'Congo',
		flag: '🇨🇬'
	},
	{ 
		abbr: 'CD', 
		name: 'Congo, the Democratic Republic of the',
		flag: '🇨🇩'
	},
	{ 
		abbr: 'CK', 
		name: 'Cook Islands',
		flag: '🇨🇰'
	},
	{ 
		abbr: 'CR', 
		name: 'Costa Rica',
		flag: '🇨🇷'
	},
	{ 
		abbr: 'CI', 
		name: 'Côte d\'Ivoire',
		flag: '🇨🇮'
	},
	{ 
		abbr: 'HR', 
		name: 'Croatia',
		flag: '🇭🇷'
	},
	{ 
		abbr: 'CU', 
		name: 'Cuba',
		flag: '🇨🇺'
	},
	{ 
		abbr: 'CW', 
		name: 'Curaçao',
		flag: '🇨🇼'
	},
	{ 
		abbr: 'CY', 
		name: 'Cyprus',
		flag: '🇨🇾'
	},
	{ 
		abbr: 'CZ', 
		name: 'Czech Republic',
		flag: '🇨🇿'
	},
	{ 
		abbr: 'DK', 
		name: 'Denmark',
		flag: '🇩🇰'
	},
	{ 
		abbr: 'DJ', 
		name: 'Djibouti',
		flag: '🇩🇯'
	},
	{ 
		abbr: 'DM', 
		name: 'Dominica',
		flag: '🇩🇲'
	},
	{ 
		abbr: 'DO', 
		name: 'Dominican Republic',
		flag: '🇩🇴'
	},
	{ 
		abbr: 'EC', 
		name: 'Ecuador',
		flag: '🇪🇨'
	},
	{ 
		abbr: 'EG', 
		name: 'Egypt',
		flag: '🇪🇬'
	},
	{ 
		abbr: 'SV', 
		name: 'El Salvador',
		flag: '🇸🇻'
	},
	{ 
		abbr: 'GQ', 
		name: 'Equatorial Guinea',
		flag: '🇬🇶'
	},
	{ 
		abbr: 'ER', 
		name: 'Eritrea',
		flag: '🇪🇷'
	},
	{ 
		abbr: 'EE', 
		name: 'Estonia',
		flag: '🇪🇪'
	},
	{ 
		abbr: 'ET', 
		name: 'Ethiopia',
		flag: '🇪🇹'
	},
	{ 
		abbr: 'FK', 
		name: 'Falkland Islands (Malvinas)',
		flag: '🇫🇰'
	},
	{ 
		abbr: 'FO', 
		name: 'Faroe Islands',
		flag: '🇫🇴'
	},
	{ 
		abbr: 'FJ', 
		name: 'Fiji',
		flag: '🇫🇯'
	},
	{ 
		abbr: 'FI', 
		name: 'Finland',
		flag: '🇫🇮'
	},
	{ 
		abbr: 'FR', 
		name: 'France',
		flag: '🇫🇷'
	},
	{ 
		abbr: 'GF', 
		name: 'French Guiana',
		flag: '🇬🇫'
	},
	{ 
		abbr: 'PF', 
		name: 'French Polynesia',
		flag: '🇵🇫'
	},
	{ 
		abbr: 'TF', 
		name: 'French Southern Territories',
		flag: '🇹🇫'
	},
	{ 
		abbr: 'GA', 
		name: 'Gabon',
		flag: '🇬🇦'
	},
	{ 
		abbr: 'GM', 
		name: 'Gambia',
		flag: '🇬🇲'
	},
	{ 
		abbr: 'GE', 
		name: 'Georgia',
		flag: '🇬🇪'
	},
	{ 
		abbr: 'DE', 
		name: 'Germany',
		flag: '🇩🇪'
	},
	{ 
		abbr: 'GH', 
		name: 'Ghana',
		flag: '🇬🇭'
	},
	{ 
		abbr: 'GI', 
		name: 'Gibraltar',
		flag: '🇬🇮'
	},
	{ 
		abbr: 'GR', 
		name: 'Greece',
		flag: '🇬🇷'
	},
	{ 
		abbr: 'GL', 
		name: 'Greenland',
		flag: '🇬🇱'
	},
	{ 
		abbr: 'GD', 
		name: 'Grenada',
		flag: '🇬🇩'
	},
	{ 
		abbr: 'GP', 
		name: 'Guadeloupe',
		flag: '🇬🇵'
	},
	{ 
		abbr: 'GU', 
		name: 'Guam',
		flag: '🇬🇺'
	},
	{ 
		abbr: 'GT', 
		name: 'Guatemala',
		flag: '🇬🇹'
	},
	{ 
		abbr: 'GG', 
		name: 'Guernsey',
		flag: '🇬🇬'
	},
	{ 
		abbr: 'GN', 
		name: 'Guinea',
		flag: '🇬🇳'
	},
	{ 
		abbr: 'GW', 
		name: 'Guinea-Bissau',
		flag: '🇬🇼'
	},
	{ 
		abbr: 'GY', 
		name: 'Guyana',
		flag: '🇬🇾'
	},
	{ 
		abbr: 'HT', 
		name: 'Haiti',
		flag: '🇭🇹'
	},
	{ 
		abbr: 'HM', 
		name: 'Heard Island and McDonald Islands',
		flag: '🇭🇲'
	},
	{ 
		abbr: 'VA', 
		name: 'Holy See (Vatican City State)',
		flag: '🇻🇦'
	},
	{ 
		abbr: 'HN', 
		name: 'Honduras',
		flag: '🇭🇳'
	},
	{ 
		abbr: 'HK', 
		name: 'Hong Kong',
		flag: '🇭🇰'
	},
	{ 
		abbr: 'HU', 
		name: 'Hungary',
		flag: '🇭🇺'
	},
	{ 
		abbr: 'IS', 
		name: 'Iceland',
		flag: '🇮🇸'
	},
	{ 
		abbr: 'IN', 
		name: 'India',
		flag: '🇮🇳'
	},
	{ 
		abbr: 'ID', 
		name: 'Indonesia',
		flag: '🇮🇩'
	},
	{ 
		abbr: 'IR', 
		name: 'Iran, Islamic Republic of',
		flag: '🇮🇷'
	},
	{ 
		abbr: 'IQ', 
		name: 'Iraq',
		flag: '🇮🇶'
	},
	{ 
		abbr: 'IE', 
		name: 'Ireland',
		flag: '🇮🇪'
	},
	{ 
		abbr: 'IM', 
		name: 'Isle of Man',
		flag: '🇮🇲'
	},
	{ 
		abbr: 'IL', 
		name: 'Israel',
		flag: '🇮🇱'
	},
	{ 
		abbr: 'IT', 
		name: 'Italy',
		flag: '🇮🇹'
	},
	{ 
		abbr: 'JM', 
		name: 'Jamaica',
		flag: '🇯🇲'
	},
	{ 
		abbr: 'JP', 
		name: 'Japan',
		flag: '🇯🇵'
	},
	{ 
		abbr: 'JE', 
		name: 'Jersey',
		flag: '🇯🇪'
	},
	{ 
		abbr: 'JO', 
		name: 'Jordan',
		flag: '🇯🇴'
	},
	{ 
		abbr: 'KZ', 
		name: 'Kazakhstan',
		flag: '🇰🇿'
	},
	{ 
		abbr: 'KE', 
		name: 'Kenya',
		flag: '🇰🇪'
	},
	{ 
		abbr: 'KI', 
		name: 'Kiribati',
		flag: '🇰🇮'
	},
	{ 
		abbr: 'KP', 
		name: 'Korea, Democratic People\'s Republic of',
		flag: '🇰🇵'
	},
	{ 
		abbr: 'KR', 
		name: 'Korea, Republic of',
		flag: '🇰🇷'
	},
	{ 
		abbr: 'KW', 
		name: 'Kuwait',
		flag: '🇰🇼'
	},
	{ 
		abbr: 'KG', 
		name: 'Kyrgyzstan',
		flag: '🇰🇬'
	},
	{ 
		abbr: 'LA', 
		name: 'Lao People\'s Democratic Republic',
		flag: '🇱🇦'
	},
	{ 
		abbr: 'LV', 
		name: 'Latvia',
		flag: '🇱🇻'
	},
	{ 
		abbr: 'LB', 
		name: 'Lebanon',
		flag: '🇱🇧'
	},
	{ 
		abbr: 'LS', 
		name: 'Lesotho',
		flag: '🇱🇸'
	},
	{ 
		abbr: 'LR', 
		name: 'Liberia',
		flag: '🇱🇷'
	},
	{ 
		abbr: 'LY', 
		name: 'Libya',
		flag: '🇱🇾'
	},
	{ 
		abbr: 'LI', 
		name: 'Liechtenstein',
		flag: '🇱🇮'
	},
	{ 
		abbr: 'LT', 
		name: 'Lithuania',
		flag: '🇱🇹'
	},
	{ 
		abbr: 'LU', 
		name: 'Luxembourg',
		flag: '🇱🇺'
	},
	{ 
		abbr: 'MO', 
		name: 'Macao',
		flag: '🇲🇴'
	},
	{ 
		abbr: 'MK', 
		name: 'Macedonia, the former Yugoslav Republic of',
		flag: '🇲🇰'
	},
	{ 
		abbr: 'MG', 
		name: 'Madagascar',
		flag: '🇲🇬'
	},
	{ 
		abbr: 'MW', 
		name: 'Malawi',
		flag: '🇲🇼'
	},
	{ 
		abbr: 'MY', 
		name: 'Malaysia',
		flag: '🇲🇾'
	},
	{ 
		abbr: 'MV', 
		name: 'Maldives',
		flag: '🇲🇻'
	},
	{ 
		abbr: 'ML', 
		name: 'Mali',
		flag: '🇲🇱'
	},
	{ 
		abbr: 'MT', 
		name: 'Malta',
		flag: '🇲🇹'
	},
	{ 
		abbr: 'MH', 
		name: 'Marshall Islands',
		flag: '🇲🇭'
	},
	{ 
		abbr: 'MQ', 
		name: 'Martinique',
		flag: '🇲🇶'
	},
	{ 
		abbr: 'MR', 
		name: 'Mauritania',
		flag: '🇲🇷'
	},
	{ 
		abbr: 'MU', 
		name: 'Mauritius',
		flag: '🇲🇺'
	},
	{ 
		abbr: 'YT', 
		name: 'Mayotte',
		flag: '🇾🇹'
	},
	{ 
		abbr: 'MX', 
		name: 'Mexico',
		flag: '🇲🇽'
	},
	{ 
		abbr: 'FM', 
		name: 'Micronesia, Federated States of',
		flag: '🇫🇲'
	},
	{ 
		abbr: 'MD', 
		name: 'Moldova, Republic of',
		flag: '🇲🇩'
	},
	{ 
		abbr: 'MC', 
		name: 'Monaco',
		flag: '🇲🇨'
	},
	{ 
		abbr: 'MN', 
		name: 'Mongolia',
		flag: '🇲🇳'
	},
	{ 
		abbr: 'ME', 
		name: 'Montenegro',
		flag: '🇲🇪'
	},
	{ 
		abbr: 'MS', 
		name: 'Montserrat',
		flag: '🇲🇸'
	},
	{ 
		abbr: 'MA', 
		name: 'Morocco',
		flag: '🇲🇦'
	},
	{ 
		abbr: 'MZ', 
		name: 'Mozambique',
		flag: '🇲🇿'
	},
	{ 
		abbr: 'MM', 
		name: 'Myanmar',
		flag: '🇲🇲'
	},
	{ 
		abbr: 'NA', 
		name: 'Namibia',
		flag: '🇳🇦'
	},
	{ 
		abbr: 'NR', 
		name: 'Nauru',
		flag: '🇳🇷'
	},
	{ 
		abbr: 'NP', 
		name: 'Nepal',
		flag: '🇳🇵'
	},
	{ 
		abbr: 'NL', 
		name: 'Netherlands',
		flag: '🇳🇱'
	},
	{ 
		abbr: 'NC', 
		name: 'New Caledonia',
		flag: '🇳🇨'
	},
	{ 
		abbr: 'NZ', 
		name: 'New Zealand',
		flag: '🇳🇿'
	},
	{ 
		abbr: 'NI', 
		name: 'Nicaragua',
		flag: '🇳🇮'
	},
	{ 
		abbr: 'NE', 
		name: 'Niger',
		flag: '🇳🇪'
	},
	{ 
		abbr: 'NG', 
		name: 'Nigeria',
		flag: '🇳🇬'
	},
	{ 
		abbr: 'NU', 
		name: 'Niue',
		flag: '🇳🇺'
	},
	{ 
		abbr: 'NF', 
		name: 'Norfolk Island',
		flag: '🇳🇫'
	},
	{ 
		abbr: 'MP', 
		name: 'Northern Mariana Islands',
		flag: '🇲🇵'
	},
	{ 
		abbr: 'NO', 
		name: 'Norway',
		flag: '🇳🇴'
	},
	{ 
		abbr: 'OM', 
		name: 'Oman',
		flag: '🇴🇲'
	},
	{ 
		abbr: 'PK', 
		name: 'Pakistan',
		flag: '🇵🇰'
	},
	{ 
		abbr: 'PW', 
		name: 'Palau',
		flag: '🇵🇼'
	},
	{ 
		abbr: 'PS', 
		name: 'Palestinian Territory, Occupied',
		flag: '🇵🇸'
	},
	{ 
		abbr: 'PA', 
		name: 'Panama',
		flag: '🇵🇦'
	},
	{ 
		abbr: 'PG', 
		name: 'Papua New Guinea',
		flag: '🇵🇬'
	},
	{ 
		abbr: 'PY', 
		name: 'Paraguay',
		flag: '🇵🇾'
	},
	{ 
		abbr: 'PE', 
		name: 'Peru',
		flag: '🇵🇪'
	},
	{ 
		abbr: 'PH', 
		name: 'Philippines',
		flag: '🇵🇭'
	},
	{ 
		abbr: 'PN', 
		name: 'Pitcairn',
		flag: '🇵🇳'
	},
	{ 
		abbr: 'PL', 
		name: 'Poland',
		flag: '🇵🇱'
	},
	{ 
		abbr: 'PT', 
		name: 'Portugal',
		flag: '🇵🇹'
	},
	{ 
		abbr: 'PR', 
		name: 'Puerto Rico',
		flag: '🇵🇷'
	},
	{ 
		abbr: 'QA', 
		name: 'Qatar',
		flag: '🇶🇦'
	},
	{ 
		abbr: 'RE', 
		name: 'Réunion',
		flag: '🇷🇪'
	},
	{ 
		abbr: 'RO', 
		name: 'Romania',
		flag: '🇷🇴'
	},
	{ 
		abbr: 'RU', 
		name: 'Russian Federation',
		flag: '🇷🇺'
	},
	{ 
		abbr: 'RW', 
		name: 'Rwanda',
		flag: '🇷🇼'
	},
	{ 
		abbr: 'BL', 
		name: 'Saint Barthélemy',
		flag: '🇧🇱'
	},
	{ 
		abbr: 'SH', 
		name: 'Saint Helena, Ascension and Tristan da Cunha',
		flag: '🇸🇭'
	},
	{ 
		abbr: 'KN', 
		name: 'Saint Kitts and Nevis',
		flag: '🇰🇳'
	},
	{ 
		abbr: 'LC', 
		name: 'Saint Lucia',
		flag: '🇱🇨'
	},
	{ 
		abbr: 'MF', 
		name: 'Saint Martin (French part)',
		flag: '🇲🇫'
	},
	{ 
		abbr: 'PM', 
		name: 'Saint Pierre and Miquelon',
		flag: '🇵🇲'
	},
	{ 
		abbr: 'VC', 
		name: 'Saint Vincent and the Grenadines',
		flag: '🇻🇨'
	},
	{ 
		abbr: 'WS', 
		name: 'Samoa',
		flag: '🇼🇸'
	},
	{ 
		abbr: 'SM', 
		name: 'San Marino',
		flag: '🇸🇲'
	},
	{ 
		abbr: 'ST', 
		name: 'Sao Tome and Principe',
		flag: '🇸🇹'
	},
	{ 
		abbr: 'SA', 
		name: 'Saudi Arabia',
		flag: '🇸🇦'
	},
	{ 
		abbr: 'SN', 
		name: 'Senegal',
		flag: '🇸🇳'
	},
	{ 
		abbr: 'RS', 
		name: 'Serbia',
		flag: '🇷🇸'
	},
	{ 
		abbr: 'SC', 
		name: 'Seychelles',
		flag: '🇸🇨'
	},
	{ 
		abbr: 'SL', 
		name: 'Sierra Leone',
		flag: '🇸🇱'
	},
	{ 
		abbr: 'SG', 
		name: 'Singapore',
		flag: '🇸🇬'
	},
	{ 
		abbr: 'SX', 
		name: 'Sint Maarten (Dutch part)',
		flag: '🇸🇽'
	},
	{ 
		abbr: 'SK', 
		name: 'Slovakia',
		flag: '🇸🇰'
	},
	{ 
		abbr: 'SI', 
		name: 'Slovenia',
		flag: '🇸🇮'
	},
	{ 
		abbr: 'SB', 
		name: 'Solomon Islands',
		flag: '🇸🇧'
	},
	{ 
		abbr: 'SO', 
		name: 'Somalia',
		flag: '🇸🇴'
	},
	{ 
		abbr: 'ZA', 
		name: 'South Africa',
		flag: '🇿🇦'
	},
	{ 
		abbr: 'GS', 
		name: 'South Georgia and the South Sandwich Islands',
		flag: '🇬🇸'
	},
	{ 
		abbr: 'SS', 
		name: 'South Sudan',
		flag: '🇸🇸'
	},
	{ 
		abbr: 'ES', 
		name: 'Spain',
		flag: '🇪🇸'
	},
	{ 
		abbr: 'LK', 
		name: 'Sri Lanka',
		flag: '🇱🇰'
	},
	{ 
		abbr: 'SD', 
		name: 'Sudan',
		flag: '🇸🇩'
	},
	{ 
		abbr: 'SR', 
		name: 'Suriname',
		flag: '🇸🇷'
	},
	{ 
		abbr: 'SJ', 
		name: 'Svalbard and Jan Mayen',
		flag: '🇸🇯'
	},
	{ 
		abbr: 'SZ', 
		name: 'Swaziland',
		flag: '🇸🇿'
	},
	{ 
		abbr: 'SE', 
		name: 'Sweden',
		flag: '🇸🇪'
	},
	{ 
		abbr: 'CH', 
		name: 'Switzerland',
		flag: '🇨🇭'
	},
	{ 
		abbr: 'SY', 
		name: 'Syrian Arab Republic',
		flag: '🇸🇾'
	},
	{ 
		abbr: 'TW', 
		name: 'Taiwan',
		flag: '🇹🇼'
	},
	{ 
		abbr: 'TJ', 
		name: 'Tajikistan',
		flag: '🇹🇯'
	},
	{ 
		abbr: 'TZ', 
		name: 'Tanzania, United Republic of',
		flag: '🇹🇿'
	},
	{ 
		abbr: 'TH', 
		name: 'Thailand',
		flag: '🇹🇭'
	},
	{ 
		abbr: 'TL', 
		name: 'Timor-Leste',
		flag: '🇹🇱'
	},
	{ 
		abbr: 'TG', 
		name: 'Togo',
		flag: '🇹🇬'
	},
	{ 
		abbr: 'TK', 
		name: 'Tokelau',
		flag: '🇹🇰'
	},
	{ 
		abbr: 'TO', 
		name: 'Tonga',
		flag: '🇹🇴'
	},
	{ 
		abbr: 'TT', 
		name: 'Trinidad and Tobago',
		flag: '🇹🇹'
	},
	{ 
		abbr: 'TN', 
		name: 'Tunisia',
		flag: '🇹🇳'
	},
	{ 
		abbr: 'TR', 
		name: 'Turkey',
		flag: '🇹🇷'
	},
	{ 
		abbr: 'TM', 
		name: 'Turkmenistan',
		flag: '🇹🇲'
	},
	{ 
		abbr: 'TC', 
		name: 'Turks and Caicos Islands',
		flag: '🇹🇨'
	},
	{ 
		abbr: 'TV', 
		name: 'Tuvalu',
		flag: '🇹🇻'
	},
	{ 
		abbr: 'UG', 
		name: 'Uganda',
		flag: '🇺🇬'
	},
	{ 
		abbr: 'UA', 
		name: 'Ukraine',
		flag: '🇺🇦'
	},
	{ 
		abbr: 'AE', 
		name: 'United Arab Emirates',
		flag: '🇦🇪'
	},
	{ 
		abbr: 'GB', 
		name: 'United Kingdom',
		flag: '🇬🇧'
	},
	{ 
		abbr: 'US', 
		name: 'United States',
		flag: '🇺🇸'
	},
	{ 
		abbr: 'UM', 
		name: 'United States Minor Outlying Islands',
		flag: '🇺🇲'
	},
	{ 
		abbr: 'UY', 
		name: 'Uruguay',
		flag: '🇺🇾'
	},
	{ 
		abbr: 'UZ', 
		name: 'Uzbekistan',
		flag: '🇺🇿'
	},
	{ 
		abbr: 'VU', 
		name: 'Vanuatu',
		flag: '🇻🇺'
	},
	{ 
		abbr: 'VE', 
		name: 'Venezuela, Bolivarian Republic of',
		flag: '🇻🇪'
	},
	{ 
		abbr: 'VN', 
		name: 'Viet Nam',
		flag: '🇻🇳'
	},
	{ 
		abbr: 'VG', 
		name: 'Virgin Islands, British',
		flag: '🇻🇬'
	},
	{ 
		abbr: 'VI', 
		name: 'Virgin Islands, U.S.',
		flag: '🇻🇮'
	},
	{ 
		abbr: 'WF', 
		name: 'Wallis and Futuna',
		flag: '🇼🇫'
	},
	{ 
		abbr: 'EH', 
		name: 'Western Sahara',
		flag: '🇪🇭'
	},
	{ 
		abbr: 'YE', 
		name: 'Yemen',
		flag: '🇾🇪'
	},
	{ 
		abbr: 'ZM', 
		name: 'Zambia',
		flag: '🇿🇲'
	},
	{ 
		abbr: 'ZW', 
		name: 'Zimbabwe',
		flag: '🇿🇼'
	}
];

export const useCountries = () => {
	const findFlag = ( name ) => {
		return countries.find(country => country.name === name)?.flag;
	}

	return {
		countries,
		findFlag
	}
}