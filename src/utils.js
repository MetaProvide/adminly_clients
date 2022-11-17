import axios from "@nextcloud/axios";
import { getTimezoneOffset } from "date-fns-tz";

export const SessionsUtil = {
	fetchSessions: async (id) => {
		const url = `/apps/adminly_clients/client/${id}/sessions`;
		return axios
			.get(url)
			.then((resp) => {
				if (resp.status !== 200)
					throw new Error("Error fetching sessions");

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
				email: client.email,
				phoneNumber: client.phoneNumber,
			})
			.then((resp) => {
				if (resp.status !== 200)
					throw new Error("Error updating client");
				return resp;
			})
			.catch((err) => {
				return err;
			});
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
	getTotalClients: async () => {
		const url = "/apps/adminly_clients/totalClients";
		return axios
			.get(url)
			.then((resp) => {
				if (resp.status !== 200)
					throw new Error("Error fetching total clients");
				return resp.data;
			})
			.catch((err) => console.error(err));
	},
	fetchPage: async (pageNumber, clientsPerPage) => {
		const url = "/apps/adminly_clients/getPage";
		return axios
			.get(url, { params: { pageNumber, clientsPerPage } })
			.then((resp) => {
				if (resp.status !== 200)
					throw new Error("Error fetching clients");
				return resp.data;
			})
			.catch((err) => console.error(err));
	},
	getClient: (clientId) => {
		const url = "/apps/adminly_clients/getClient";
		return axios
			.get(url, {
				params: {
					id: clientId,
				},
			})
			.then((resp) => {
				if (resp.status !== 200)
					throw new Error("Error fetching client");
				return resp.data;
			})
			.catch((err) => console.error(err));
	},
};

export const TimezoneUtil = {
	timezoneWithUTC: (timezone) => {
		const offset = getTimezoneOffset(timezone) / 3600000;
		let offsetWithSign = offset > 0 ? `+${offset}` : offset;
		const location = timezone === "UTC" ? "" : timezone.replace("_", " ");

		if (offset === 0) offsetWithSign = "";

		return `${location} UTC${offsetWithSign}`;
	},
};
