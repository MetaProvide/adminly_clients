import axios from "@nextcloud/axios";

export const SessionsUtil = {
	fetchSessions: async (id) => {
		const url = `/apps/adminly_clients/client/${id}/sessions`;
		return axios
			.get(url)
			.then((resp) => {
				if (resp.status !== 200)
					throw new Error("Error fetching clients");

				return resp.data.map((elm) => ({
					...elm,
					date: new Date(elm.date).toLocaleString(),
				}));
			})
			.catch((err) => console.error(err));
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
