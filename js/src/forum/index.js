import { extend } from 'flarum/extend';
import app from 'flarum/app';
import LogInButtons from 'flarum/components/LogInButtons';
import LogInButton from 'flarum/components/LogInButton';

app.initializers.add('dem13n-auth-ok', () => {
  extend(LogInButtons.prototype, 'items', function(items) {
    items.add('ok',
      <LogInButton
        className="Button LogInButton--ok"
        icon="fab fa-odnoklassniki"
        path="/auth/ok">
        {app.translator.trans('dem13n-auth-ok.forum.login_with_ok_button')}
      </LogInButton>
    );
  });
});
