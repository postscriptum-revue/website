panel.plugin("postscriptum/article-pdf", {
	sections: {
		"article-pdf": {
			data() {
				return {
					loading: false,
					message: ""
				};
			},
			computed: {
				encodedId() {
					return this.parent.replace("/pages/", "");
				}
			},
			methods: {
				async generate() {
					this.loading = true;
					this.message = "";

					try {
						const response = await this.$api.post(
							"article-pdf/" + this.encodedId
						);

						if (response.status === "ok") {
							this.message = "PDF généré : " + response.filename;
							this.$reload();
						} else {
							this.message = "Erreur : " + response.message;
						}
					} catch (e) {
						this.message = "Erreur lors de la génération.";
					}

					this.loading = false;
				}
			},
			template: `
				<k-section label="Générer le PDF">
					<k-button
						icon="download"
						theme="positive"
						variant="filled"
						size="sm"
						:disabled="loading"
						@click="generate">
						{{ loading ? "Génération en cours…" : "Générer le PDF" }}
					</k-button>
					<k-text v-if="message" theme="help" style="margin-top: 0.5em">
						{{ message }}
					</k-text>
				</k-section>
			`
		}
	}
});
