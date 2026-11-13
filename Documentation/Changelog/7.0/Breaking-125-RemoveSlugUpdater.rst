.. include:: ../../Includes.rst.txt

=====================================
Breaking: #125 - Remove slug updater
=====================================

See `Pull Request 125 <https://github.com/extcode/cart_events/pull/125>`__

Description
===========

The upgrade wizard `cartEventsSlugUpdater`
(`Extcode\CartEvents\Updates\SlugUpdater`) is removed. It generated the
slug field `path_segment` for events without a slug.

Affected Installations
======================

All installations with events without a slug that did not run the
upgrade wizard yet.

Migration
=========

Run the upgrade wizard "Updates slug field "path_segment" of
EXT:cart_events records" with cart_events 6.x before upgrading to 7.0.
