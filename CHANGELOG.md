# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres
to [Semantic Versioning](http://semver.org/).

## 0.2.2
### Fixed
- Tracking of orders placed using the App

## 0.2.1
### Fixed
- Error when calling id endpoint for products with multiple parent products

## 0.2.0

### Added
- SKU to ID endpoint
- Anonymous token endpoint for a guest user that has no cart
### Changed
- Default login redirect route to point to /checkout instead of cart page
### Fixed
- Support for Safari inApp browser on IOS devices

## 0.1.0

### Added
- API controllers (login, ID to SKU, logger)
- Page controllers
- CSS configurations
- Tests
