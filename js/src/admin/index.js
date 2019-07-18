import app from 'flarum/app';

import OdnoklassnikiSettingsModal from './components/OdnoklassnikiSettingsModal';

app.initializers.add('dem13n-auth-odnoklassniki', () => {
  app.extensionSettings['dem13n-auth-odnoklassniki'] = () => app.modal.show(new OdnoklassnikiSettingsModal());
});
