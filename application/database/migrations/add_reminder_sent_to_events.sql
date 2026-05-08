-- Migration: Add reminder_sent flag to events table
-- Run this once on the server before deploying the reminder feature

ALTER TABLE `events`
  ADD COLUMN `reminder_sent` TINYINT(1) NOT NULL DEFAULT 0
    COMMENT '0 = not sent, 1 = reminder sent';
