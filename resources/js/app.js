import Axios from 'axios';

window.axios = Axios.create()
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// window.axios.defaults.headers.common['Content-Type'] = 'application/json';
// window.axios.defaults.headers.common['Accept'] = 'application/json';

import './alpineComponents/users';
import './alpineComponents/roles';
import './alpineComponents/permissions';
import './alpineComponents/companies';
import './alpineComponents/employees';
import './alpineComponents/documents';
import './alpineComponents/quotations';
import './alpineComponents/titles';
import './alpineComponents/assets';
import './alpineComponents/asset_types';
import './alpineComponents/projects';
import './alpineStores/attachments';
