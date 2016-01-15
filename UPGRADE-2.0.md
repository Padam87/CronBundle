# Upgrade guide from 1.x to 2.0

## Configuration

### Before

```yaml
padam87_cron:
    log_dir: %cron_log_dir%
    mailto: %cron_mailto%
    path: %cron_path%
```

### After

```yaml
padam87_cron:
    log_dir: %cron_log_dir%
    variables:
        mailto: %cron_mailto%
        any_other_variable_you_might_need: 'some_value'
```

## Usage

No major changes, but the `mailto` and `log-dir` options have been removed from the commands.
These still can and should be set in the configuration.

## Other

- The `Helper` util has been changed. If you were extending that class, you should take a look.
