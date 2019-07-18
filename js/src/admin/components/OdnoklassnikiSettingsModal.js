import SettingsModal from 'flarum/components/SettingsModal';

export default class OdnoklassnikiSettingsModal extends SettingsModal {
  className() {
    return 'OdnoklassnikiSettingsModal Modal--small';
  }

  title() {
    return app.translator.trans('dem13n-auth-ok.admin.ok_settings.title');
  }

  form() {
    return [
      <div className="Form-group">
      <label>{app.translator.trans('dem13n-auth-ok.admin.ok_settings.app_desc', { a: <a href="https://ok.ru/dk?st.cmd=appEditBasic&st.vpl.mini=false" target="_blank" /> })}</label>
    <label>{app.translator.trans("dem13n-auth-ok.admin.ok_settings.app_p")}</label>
    <b>{document.location.origin + "/auth/ok"}</b>
    </div>,

      <div className="Form-group">
        <label>{app.translator.trans('dem13n-auth-ok.admin.ok_settings.app_id_label')}</label>
        <input className="FormControl" bidi={this.setting('dem13n-auth-ok.app_id')}/>
      </div>,

    <div className="Form-group">
      <label>{app.translator.trans('dem13n-auth-ok.admin.ok_settings.app_public_label')}</label>
      <input className="FormControl" bidi={this.setting('dem13n-auth-ok.app_public')}/>
    </div>,

      <div className="Form-group">
        <label>{app.translator.trans('dem13n-auth-ok.admin.ok_settings.app_secret_label')}</label>
        <input className="FormControl" bidi={this.setting('dem13n-auth-ok.app_secret')}/>
      </div>
    ];
  }
}
