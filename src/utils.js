import axios from "@nextcloud/axios";

export const ClientsUtil = {
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
