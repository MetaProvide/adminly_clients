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
