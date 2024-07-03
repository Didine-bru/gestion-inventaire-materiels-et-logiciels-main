function fetchSearch(_this, baseUrl) {
	if (!_this.searchText) return;

	_this.loading = true;
	fetch(`${baseUrl}/search?text=${_this.searchText}`, {
		method: "GET",
	})
		.then((response) => response.json())
		.then((response) => {
			// console.log(response.data);
			_this.data = response.data;
			_this.loading = false;
		});
}

function fetchDeleteItem(_this, formUrl) {
	fetch(`${formUrl}/delete/${_this.itemToDelete.id}`)
		.then((response) => response.json())
		.then((response) => {
			if (response.data.success) {
				_this.deleteModalOpen = false;
				_this.getData();
				_this.itemToDelete = null;
			} else {
				_this.formMessage = response.message;
			}
		});
}

function fetchData(_this, dataUrl) {
	_this.loading = true;
	fetch(dataUrl, {
		method: "GET",
	})
		.then((response) => response.json())
		.then((response) => {
			// console.log(response);
			_this.data = response.data;
			_this.loading = false;
		});
}

function submitForm(_this, baseUrl) {
	// console.log(_this.formData);
	_this.formLoading = true;
	let formData = new FormData();
	for (let key in _this.formData) {
		if (typeof _this.formData[key] == "boolean") {
			formData.append(key, _this.formData[key] ? 1 : 0);
		} else if (
			_this.formData[key] != null &&
			_this.formData[key] != "" &&
			_this.formData[key] != []
		) {
			formData.append(key, _this.formData[key]);
		}
	}
	let url = _this.isEdit
		? `${baseUrl}/edit/${_this.itemId}`
		: `${baseUrl}/create`;
	fetch(url, {
		method: "POST",
		body: formData,
	})
		.then((response) => response.json())
		.then((response) => {
			if (response.data.success) {
				_this.modalOpen = false;
				_this.getData();
			} else {
				_this.formMessage = response.message;
				if (_this.formData.hasOwnProperty("password")) {
					_this.formData.password = "";
					_this.formData.password_confirm = "";
				}
			}
			_this.formLoading = false;
		});
}

function valuesToFormData(_this, values, type=null, formData = "formData") {
	switch (type) {
		case "administrator":
			for (let key in _this[formData]) {
				_this[formData][key] = values[key];
			}
			_this[formData].is_admin = values.groups.includes("admin");
			break;
		case "hardware":
			for (let key in _this[formData]) {
				if (values[key] != undefined) _this[formData][key] = values[key];
			}
      _this[formData].under_maintenance = values.under_maintenance == 1;
			if (values.personnel) _this[formData].personnel_id = values.personnel.id;
			break;
		case "software":
			for (let key in _this[formData]) {
				if (values.hasOwnProperty(key)) _this[formData][key] = values[key];
			}
			if (values.license) _this[formData].license_id = values.license.id;
			if (values.personnels) {
				_this[formData].personnel_ids = [];
				for (let personnel of values.personnels)
					_this[formData].personnel_ids.push(personnel.id);
			}
			break;
		default:
			for (let key in _this[formData]) {
				_this[formData][key] = values[key];
			}
	}
  if (formData === "formData") {
    _this.itemId = values.id;
    _this.isEdit = true;
  }
}

function formDataOf(type) {
  switch (type) {
    case 'administrator':
      return {
				identity: '',
				first_name: '',
				last_name: '',
				email: '',
				password: '',
				password_confirm: '',
				is_admin: false
			};
    case 'personnel':
      return {
				serial_number: '',
				name: '',
				departement: '',
				post: '',
			};
    case 'hardware':
      return {
				serial_number: '',
				model: '',
				color: '',
				category: '',
				manufacturer: '',
				date_purchase: '',
				date_warranty_expiration: '',
				under_maintenance: false,
				personnel_id: '',
			};
    case 'software':
      return {
				name: '',
				version: '',
				date_purchase: '',
				date_warranty_expiration: '',
				license_id: '',
				personnel_ids: [],
			};
    case 'license':
      return {
				license_number: '',
				name: '',
				number_of_licenses: '',
				date_expiration: '',
			};
  }
}

function requestStruct(baseUrl, type) {
  return {
    data: [],
    // page: 1,
    modalOpen: false,
    loading: false,
    getData() {
      fetchData(this, `${baseUrl}/${type}s`);
    },
    init() {
      this.getData();
    },
    itemId: null,
    isEdit: false,
    formData: formDataOf(type),
    formLoading: false,
    formMessage: "",
    initializeFormData(values = null) {
      if (values) {
        valuesToFormData(this, values, type);
      } else {
        this.formData = formDataOf(type);
        this.itemId = null;
        this.isEdit = false;
      }
      this.formLoading = false;
      this.formMessage = "";
    },
    submitForm() {
      submitForm(this, baseUrl);
    },
    deleteModalOpen: false,
    itemToDelete: null,
    deleteItem() {
      fetchDeleteItem(this, baseUrl);
    },
    searchText: '',
    submitSearch() {
      fetchSearch(this, baseUrl);
    }
  }
}