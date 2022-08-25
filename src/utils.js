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

export const AdminlyUtil = {
	getAdminlyColor: (string) => {
		const adminlyColors = [
			"#8AE21A",
			"#E21A7A",
			"#D21AE2",
			"#5A1AE2",
			"#1A76E2",
			"#1AB2E2",
			"#1AE2D6",
			"#1AE2A6",
			"#010564",
			"#F68500",
		];
		const arrayLength = adminlyColors.length;
		const index =
			string.length > arrayLength
				? string.length - arrayLength
				: string.length;
		return adminlyColors[index];
	},
};
