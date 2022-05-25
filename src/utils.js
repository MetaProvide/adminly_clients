import axios from "@nextcloud/axios";

export const SessionsUtil = {
	fetchSessions: () => {
		// TODO
		return [
			{
				description: "Hi, This is a sample session description",
				date: "06/04/2022",
				mainTitle: "Therapy Session",
				paid: true,
				id: 1,
				value: "50€/50€",
			},
			{
				description: "Hi, This is a sample session description",
				mainTitle: "Therapy Session",
				date: "07/04/2022",
				paid: false,
				id: 2,
				value: "20€/50€",
			},
		];
	},
};

export const ClientsUtil = {
	updateClient: (client) => {
		const url = "/apps/adminly_clients/update";
		return axios
			.post(url, {
				id: client.id,
				name: client.name,
				description: client.description,
				timezone: client.timezone,
				country: client.country,
				city: client.city,
				age: client.age,
				contacts: client.contacts,
			})
			.then((resp) => {
				if (resp.status !== 200)
					throw new Error("Error updating client");
			})
			.catch((err) => console.error(err));
	},
	fetchClients: async () => {
		const url = "/apps/adminly_clients/get";
		return axios
			.get(url)
			.then((resp) => {
				if (resp.status !== 200)
					throw new Error("Error fetching clients");
				return resp.data;
			})
			.catch((err) => console.error(err));
	},
};

export const TimezoneUtil = {
	getTimezoneNames: () => {
		return [
			"Africa/Abidjan",
			"Africa/Accra",
			"Africa/Algiers",
			"Africa/Bissau",
			"Africa/Cairo",
			"Africa/Casablanca",
			"Africa/Ceuta",
			"Africa/El Aaiun",
			"Africa/Johannesburg",
			"Africa/Juba",
			"Africa/Khartoum",
			"Africa/Lagos",
			"Africa/Maputo",
			"Africa/Monrovia",
			"Africa/Nairobi",
			"Africa/Ndjamena",
			"Africa/Sao Tome",
			"Africa/Tripoli",
			"Africa/Tunis",
			"Africa/Windhoek",
			"America/Adak",
			"America/Anchorage",
			"America/Araguaina",
			"America/Argentina/Buenos Aires",
			"America/Argentina/Catamarca",
			"America/Argentina/Cordoba",
			"America/Argentina/Jujuy",
			"America/Argentina/La Rioja",
			"America/Argentina/Mendoza",
			"America/Argentina/Rio Gallegos",
			"America/Argentina/Salta",
			"America/Argentina/San Juan",
			"America/Argentina/San Luis",
			"America/Argentina/Tucuman",
			"America/Argentina/Ushuaia",
			"America/Asuncion",
			"America/Atikokan",
			"America/Bahia",
			"America/Bahia Banderas",
			"America/Barbados",
			"America/Belem",
			"America/Belize",
			"America/Blanc-Sablon",
			"America/Boa Vista",
			"America/Bogota",
			"America/Boise",
			"America/Cambridge Bay",
			"America/Campo Grande",
			"America/Cancun",
			"America/Caracas",
			"America/Cayenne",
			"America/Chicago",
			"America/Chihuahua",
			"America/Costa Rica",
			"America/Creston",
			"America/Cuiaba",
			"America/Curacao",
			"America/Danmarkshavn",
			"America/Dawson",
			"America/Dawson Creek",
			"America/Denver",
			"America/Detroit",
			"America/Edmonton",
			"America/Eirunepe",
			"America/El Salvador",
			"America/Fort Nelson",
			"America/Fortaleza",
			"America/Glace Bay",
			"America/Goose Bay",
			"America/Grand Turk",
			"America/Guatemala",
			"America/Guayaquil",
			"America/Guyana",
			"America/Halifax",
			"America/Havana",
			"America/Hermosillo",
			"America/Indiana/Indianapolis",
			"America/Indiana/Knox",
			"America/Indiana/Marengo",
			"America/Indiana/Petersburg",
			"America/Indiana/Tell City",
			"America/Indiana/Vevay",
			"America/Indiana/Vincennes",
			"America/Indiana/Winamac",
			"America/Inuvik",
			"America/Iqaluit",
			"America/Jamaica",
			"America/Juneau",
			"America/Kentucky/Louisville",
			"America/Kentucky/Monticello",
			"America/La Paz",
			"America/Lima",
			"America/Los Angeles",
			"America/Maceio",
			"America/Managua",
			"America/Manaus",
			"America/Martinique",
			"America/Matamoros",
			"America/Mazatlan",
			"America/Menominee",
			"America/Merida",
			"America/Metlakatla",
			"America/Mexico City",
			"America/Miquelon",
			"America/Moncton",
			"America/Monterrey",
			"America/Montevideo",
			"America/Nassau",
			"America/New York",
			"America/Nipigon",
			"America/Nome",
			"America/Noronha",
			"America/North Dakota/Beulah",
			"America/North Dakota/Center",
			"America/North Dakota/New Salem",
			"America/Nuuk",
			"America/Ojinaga",
			"America/Panama",
			"America/Pangnirtung",
			"America/Paramaribo",
			"America/Phoenix",
			"America/Port-au-Prince",
			"America/Port of Spain",
			"America/Porto Velho",
			"America/Puerto Rico",
			"America/Punta Arenas",
			"America/Rainy River",
			"America/Rankin Inlet",
			"America/Recife",
			"America/Regina",
			"America/Resolute",
			"America/Rio Branco",
			"America/Santarem",
			"America/Santiago",
			"America/Santo Domingo",
			"America/Sao Paulo",
			"America/Scoresbysund",
			"America/Sitka",
			"America/St Johns",
			"America/Swift Current",
			"America/Tegucigalpa",
			"America/Thule",
			"America/Thunder Bay",
			"America/Tijuana",
			"America/Toronto",
			"America/Vancouver",
			"America/Whitehorse",
			"America/Winnipeg",
			"America/Yakutat",
			"America/Yellowknife",
			"Antarctica/Casey",
			"Antarctica/Davis",
			"Antarctica/DumontDUrville",
			"Antarctica/Macquarie",
			"Antarctica/Mawson",
			"Antarctica/Palmer",
			"Antarctica/Rothera",
			"Antarctica/Syowa",
			"Antarctica/Troll",
			"Antarctica/Vostok",
			"Asia/Almaty",
			"Asia/Amman",
			"Asia/Anadyr",
			"Asia/Aqtau",
			"Asia/Aqtobe",
			"Asia/Ashgabat",
			"Asia/Atyrau",
			"Asia/Baghdad",
			"Asia/Baku",
			"Asia/Bangkok",
			"Asia/Barnaul",
			"Asia/Beirut",
			"Asia/Bishkek",
			"Asia/Brunei",
			"Asia/Chita",
			"Asia/Choibalsan",
			"Asia/Colombo",
			"Asia/Damascus",
			"Asia/Dhaka",
			"Asia/Dili",
			"Asia/Dubai",
			"Asia/Dushanbe",
			"Asia/Famagusta",
			"Asia/Gaza",
			"Asia/Hebron",
			"Asia/Ho Chi Minh",
			"Asia/Hong Kong",
			"Asia/Hovd",
			"Asia/Irkutsk",
			"Asia/Jakarta",
			"Asia/Jayapura",
			"Asia/Jerusalem",
			"Asia/Kabul",
			"Asia/Kamchatka",
			"Asia/Karachi",
			"Asia/Kathmandu",
			"Asia/Khandyga",
			"Asia/Kolkata",
			"Asia/Krasnoyarsk",
			"Asia/Kuala Lumpur",
			"Asia/Kuching",
			"Asia/Macau",
			"Asia/Magadan",
			"Asia/Makassar",
			"Asia/Manila",
			"Asia/Nicosia",
			"Asia/Novokuznetsk",
			"Asia/Novosibirsk",
			"Asia/Omsk",
			"Asia/Oral",
			"Asia/Pontianak",
			"Asia/Pyongyang",
			"Asia/Qatar",
			"Asia/Qostanay",
			"Asia/Qyzylorda",
			"Asia/Riyadh",
			"Asia/Sakhalin",
			"Asia/Samarkand",
			"Asia/Seoul",
			"Asia/Shanghai",
			"Asia/Singapore",
			"Asia/Srednekolymsk",
			"Asia/Taipei",
			"Asia/Tashkent",
			"Asia/Tbilisi",
			"Asia/Tehran",
			"Asia/Thimphu",
			"Asia/Tokyo",
			"Asia/Tomsk",
			"Asia/Ulaanbaatar",
			"Asia/Urumqi",
			"Asia/Ust-Nera",
			"Asia/Vladivostok",
			"Asia/Yakutsk",
			"Asia/Yangon",
			"Asia/Yekaterinburg",
			"Asia/Yerevan",
			"Atlantic/Azores",
			"Atlantic/Bermuda",
			"Atlantic/Canary",
			"Atlantic/Cape Verde",
			"Atlantic/Faroe",
			"Atlantic/Madeira",
			"Atlantic/Reykjavik",
			"Atlantic/South Georgia",
			"Atlantic/Stanley",
			"Australia/Adelaide",
			"Australia/Brisbane",
			"Australia/Broken Hill",
			"Australia/Darwin",
			"Australia/Eucla",
			"Australia/Hobart",
			"Australia/Lindeman",
			"Australia/Lord Howe",
			"Australia/Melbourne",
			"Australia/Perth",
			"Australia/Sydney",
			"CET",
			"CST6CDT",
			"EET",
			"EST",
			"EST5EDT",
			"Etc/GMT",
			"Etc/GMT+1",
			"Etc/GMT+10",
			"Etc/GMT+11",
			"Etc/GMT+12",
			"Etc/GMT+2",
			"Etc/GMT+3",
			"Etc/GMT+4",
			"Etc/GMT+5",
			"Etc/GMT+6",
			"Etc/GMT+7",
			"Etc/GMT+8",
			"Etc/GMT+9",
			"Etc/GMT-1",
			"Etc/GMT-10",
			"Etc/GMT-11",
			"Etc/GMT-12",
			"Etc/GMT-13",
			"Etc/GMT-14",
			"Etc/GMT-2",
			"Etc/GMT-3",
			"Etc/GMT-4",
			"Etc/GMT-5",
			"Etc/GMT-6",
			"Etc/GMT-7",
			"Etc/GMT-8",
			"Etc/GMT-9",
			"Etc/UTC",
			"Europe/Amsterdam",
			"Europe/Andorra",
			"Europe/Astrakhan",
			"Europe/Athens",
			"Europe/Belgrade",
			"Europe/Berlin",
			"Europe/Brussels",
			"Europe/Bucharest",
			"Europe/Budapest",
			"Europe/Chisinau",
			"Europe/Copenhagen",
			"Europe/Dublin",
			"Europe/Gibraltar",
			"Europe/Helsinki",
			"Europe/Istanbul",
			"Europe/Kaliningrad",
			"Europe/Kiev",
			"Europe/Kirov",
			"Europe/Lisbon",
			"Europe/London",
			"Europe/Luxembourg",
			"Europe/Madrid",
			"Europe/Malta",
			"Europe/Minsk",
			"Europe/Monaco",
			"Europe/Moscow",
			"Europe/Oslo",
			"Europe/Paris",
			"Europe/Prague",
			"Europe/Riga",
			"Europe/Rome",
			"Europe/Samara",
			"Europe/Saratov",
			"Europe/Simferopol",
			"Europe/Sofia",
			"Europe/Stockholm",
			"Europe/Tallinn",
			"Europe/Tirane",
			"Europe/Ulyanovsk",
			"Europe/Uzhgorod",
			"Europe/Vienna",
			"Europe/Vilnius",
			"Europe/Volgograd",
			"Europe/Warsaw",
			"Europe/Zaporozhye",
			"Europe/Zurich",
			"HST",
			"Indian/Chagos",
			"Indian/Christmas",
			"Indian/Cocos",
			"Indian/Kerguelen",
			"Indian/Mahe",
			"Indian/Maldives",
			"Indian/Mauritius",
			"Indian/Reunion",
			"MET",
			"MST",
			"MST7MDT",
			"PST8PDT",
			"Pacific/Apia",
			"Pacific/Auckland",
			"Pacific/Bougainville",
			"Pacific/Chatham",
			"Pacific/Chuuk",
			"Pacific/Easter",
			"Pacific/Efate",
			"Pacific/Enderbury",
			"Pacific/Fakaofo",
			"Pacific/Fiji",
			"Pacific/Funafuti",
			"Pacific/Galapagos",
			"Pacific/Gambier",
			"Pacific/Guadalcanal",
			"Pacific/Guam",
			"Pacific/Honolulu",
			"Pacific/Kiritimati",
			"Pacific/Kosrae",
			"Pacific/Kwajalein",
			"Pacific/Majuro",
			"Pacific/Marquesas",
			"Pacific/Nauru",
			"Pacific/Niue",
			"Pacific/Norfolk",
			"Pacific/Noumea",
			"Pacific/Pago Pago",
			"Pacific/Palau",
			"Pacific/Pitcairn",
			"Pacific/Pohnpei",
			"Pacific/Port Moresby",
			"Pacific/Rarotonga",
			"Pacific/Tahiti",
			"Pacific/Tarawa",
			"Pacific/Tongatapu",
			"Pacific/Wake",
			"Pacific/Wallis",
			"WET",
		];
	},
};
