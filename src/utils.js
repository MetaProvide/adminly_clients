import axios from "@nextcloud/axios";
import { getTimezoneOffset } from "date-fns-tz";

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
				email: client.email,
				phoneNumber: client.phoneNumber,
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
	timezoneWithUTC: (timezone) => {
		const offset = getTimezoneOffset(timezone) / 3600000;
		let offsetWithSign = offset > 0 ? `+${offset}` : offset;
		const location = timezone === "UTC" ? "" : timezone.replace("_", " ");

		if (offset === 0) offsetWithSign = "";

		return `${location} UTC${offsetWithSign}`;
	},
};
