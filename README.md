# Advanced iCal Import Settings Plugin

A comprehensive plugin for importing and managing iCalendar (iCal) files with advanced configuration options and customizable import settings.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Advanced Settings](#advanced-settings)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

## Overview

The Advanced iCal Import Settings plugin provides robust functionality for importing iCalendar (.ics) files with extensive customization options. It allows users to configure import parameters, filter events, and manage calendar data according to their specific needs.

This plugin is designed to integrate seamlessly with calendar applications and provides a user-friendly interface for managing complex import scenarios.

## Features

### Core Features

- **iCal File Import**: Import standard iCalendar (.ics) files with full RFC 5545 compliance
- **Batch Processing**: Handle multiple calendar files simultaneously
- **Event Filtering**: Filter events based on date ranges, categories, and custom criteria
- **Timezone Support**: Automatic timezone detection and conversion
- **Duplicate Detection**: Identify and handle duplicate events intelligently
- **Calendar Mapping**: Map imported events to specific calendars
- **Custom Field Mapping**: Configure custom field mappings for event data

### Advanced Configuration

- **Import Rules**: Define custom rules for event processing
- **Data Validation**: Built-in validation for imported calendar data
- **Error Handling**: Comprehensive error reporting and logging
- **Scheduled Imports**: Configure automatic import schedules
- **Merge Options**: Control how imported events merge with existing data
- **Backup & Recovery**: Automatic backup creation before imports

## Installation

### Prerequisites

- Compatible calendar application
- .NET Framework 4.7.2 or higher
- Administrator privileges for installation

### Installation Steps

1. **Download the Plugin**
   ```bash
   git clone https://github.com/Luca-7JGKP/com.luca.advancedicalimport.git
   cd com.luca.advancedicalimport
   ```

2. **Build from Source**
   ```bash
   dotnet build
   ```

3. **Install to Calendar Application**
   - Locate your calendar application's plugin directory
   - Copy the compiled plugin files to the plugins folder
   - Restart the calendar application

4. **Verify Installation**
   - Open your calendar application
   - Navigate to Settings → Plugins
   - Confirm "Advanced iCal Import Settings" appears in the plugin list

## Configuration

### Initial Setup

1. **Access Plugin Settings**
   - Open your calendar application
   - Go to Settings → Plugin Settings → Advanced iCal Import Settings

2. **Configure Import Defaults**
   - Set your default import location
   - Configure default calendar for imports
   - Set timezone preferences

3. **Set File Handling Preferences**
   - Choose file storage location
   - Set file retention policies
   - Configure backup options

### Configuration File

Edit the `config.json` file in the plugin directory:

```json
{
  "defaultCalendar": "Calendar",
  "timezone": "UTC",
  "autoBackup": true,
  "backupLocation": "./backups",
  "maxImportSize": 52428800,
  "enableDuplicateDetection": true,
  "mergeStrategy": "ask",
  "logLevel": "info",
  "validateBeforeImport": true,
  "scheduleImport": {
    "enabled": false,
    "frequency": "daily",
    "time": "02:00"
  }
}
```

### Configuration Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `defaultCalendar` | string | "Calendar" | Default calendar for imported events |
| `timezone` | string | "UTC" | Default timezone for event processing |
| `autoBackup` | boolean | true | Automatically backup before import |
| `backupLocation` | string | "./backups" | Directory for backup files |
| `maxImportSize` | number | 52428800 | Maximum file size in bytes (50MB) |
| `enableDuplicateDetection` | boolean | true | Enable duplicate event detection |
| `mergeStrategy` | string | "ask" | How to handle duplicates: "ask", "skip", "replace", "merge" |
| `logLevel` | string | "info" | Logging verbosity: "debug", "info", "warn", "error" |
| `validateBeforeImport` | boolean | true | Validate calendar data before import |

## Usage

### Basic Import

1. **Open Import Dialog**
   - Go to File → Import → iCalendar File
   - Or use the keyboard shortcut: `Ctrl+I`

2. **Select iCal File**
   - Browse and select your .ics file
   - Preview events before import

3. **Configure Import Options**
   - Choose target calendar
   - Set event date range filter (if needed)
   - Select merge strategy for conflicts

4. **Complete Import**
   - Click "Import" to proceed
   - Monitor progress in the import dialog
   - View import summary with results

### Advanced Import

1. **Filter Events During Import**
   - Select "Advanced Filters" in import dialog
   - Set date range, categories, and other criteria
   - Preview filtered events

2. **Map Custom Fields**
   - Click "Field Mapping" tab
   - Configure custom field mappings
   - Apply transformations if needed

3. **Configure Merge Behavior**
   - Select "Merge Options" in import dialog
   - Choose how to handle existing events
   - Set field-level merge rules

## Advanced Settings

### Custom Import Rules

Create custom import rules by editing `rules.json`:

```json
{
  "rules": [
    {
      "name": "Work Events Only",
      "conditions": {
        "category": "Work",
        "startDate": "2025-01-01",
        "endDate": "2025-12-31"
      },
      "actions": {
        "targetCalendar": "Work Calendar",
        "setReminder": 15,
        "addTag": "imported"
      }
    }
  ]
}
```

### Scheduled Imports

Enable automatic import scheduling:

```json
{
  "scheduleImport": {
    "enabled": true,
    "frequency": "daily",
    "time": "02:00",
    "sourceFile": "/path/to/calendar.ics",
    "targetCalendar": "Calendar"
  }
}
```

### Logging and Debugging

Enable detailed logging for troubleshooting:

```json
{
  "logLevel": "debug",
  "logFile": "./logs/import.log",
  "logMaxSize": 10485760,
  "logRetention": 30
}
```

## Troubleshooting

### Common Issues

#### File Not Found
- **Problem**: "Unable to find the specified file"
- **Solution**: 
  - Verify file path is correct
  - Check file permissions
  - Ensure file hasn't been moved or deleted

#### Import Fails with Validation Error
- **Problem**: "Calendar data validation failed"
- **Solution**:
  - Validate .ics file syntax using online validators
  - Check for unsupported iCal properties
  - Try disabling validation: set `validateBeforeImport` to false

#### Duplicate Events After Import
- **Problem**: "Events appear multiple times in calendar"
- **Solution**:
  - Enable duplicate detection: set `enableDuplicateDetection` to true
  - Change merge strategy from "ask" to "skip" or "replace"
  - Check event UID values in source file

#### Timezone Issues
- **Problem**: "Events appear at wrong time"
- **Solution**:
  - Verify timezone setting in config.json
  - Check iCal file TZINFO parameters
  - Ensure calendar application timezone matches system timezone

#### Large File Import Fails
- **Problem**: "File size exceeds maximum limit"
- **Solution**:
  - Increase `maxImportSize` in config.json
  - Split large .ics files into multiple smaller files
  - Import in batches

### Debug Mode

Enable debug mode for detailed error information:

1. Set `logLevel` to "debug" in config.json
2. Check log files in the `./logs` directory
3. Share log files if reporting issues

## Contributing

We welcome contributions! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Support

For issues, questions, or suggestions:

- Open an issue on GitHub
- Check existing documentation
- Review the troubleshooting section
- Enable debug logging for detailed error information

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Version History

### v1.0.0 (Current)
- Initial release
- Core iCal import functionality
- Advanced configuration options
- Event filtering and mapping
- Scheduled import support

---

**Last Updated**: 2025-12-25

For more information and updates, visit the [GitHub Repository](https://github.com/Luca-7JGKP/com.luca.advancedicalimport)
