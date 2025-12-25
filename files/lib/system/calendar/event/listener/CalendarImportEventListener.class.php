<?php
namespace wcf\system\calendar\event\listener;

use wcf\data\calendar\event\CalendarEvent;
use wcf\data\calendar\event\CalendarEventAction;
use wcf\system\event\listener\IEventListener;
use wcf\system\exception\SystemException;

/**
 * Event listener for preserving participants during iCal synchronization.
 * 
 * This listener ensures that existing event participants are maintained
 * when calendar events are updated through iCal import processes.
 * 
 * @author Luca-7JGKP
 * @license WoltLab® Community License Agreement <https://www.woltlab.com/license/>
 */
class CalendarImportEventListener implements IEventListener
{
    /**
     * Storage for original participants data
     * 
     * @var array
     */
    private static $originalParticipants = [];

    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        // Handle pre-update event to preserve participants
        if ($eventName === 'execute' && $className === CalendarEventAction::class) {
            $this->handleEventUpdate($eventObj, $parameters);
        }
    }

    /**
     * Handles calendar event updates to preserve participant data.
     * 
     * @param CalendarEventAction $eventObj The calendar event action object
     * @param array &$parameters The action parameters
     * @throws SystemException
     */
    private function handleEventUpdate(CalendarEventAction $eventObj, array &$parameters)
    {
        // Get the action being performed
        $action = $eventObj->getActionName();

        // Only process update actions
        if ($action !== 'update') {
            return;
        }

        $objectIDs = $eventObj->getObjectIDs();

        // Process each event being updated
        foreach ($objectIDs as $eventID) {
            try {
                $event = new CalendarEvent($eventID);
                if (!$event->eventID) {
                    continue;
                }

                // Store original participants data
                $this->storeOriginalParticipants($event);
            } catch (SystemException $e) {
                // Log error but continue processing
                \wcf\functions\exception\logThrowable($e);
            }
        }

        // Restore participants after update
        $this->restoreParticipantsAfterUpdate($eventObj, $objectIDs);
    }

    /**
     * Stores the original participants data for an event.
     * 
     * @param CalendarEvent $event The calendar event
     */
    private function storeOriginalParticipants(CalendarEvent $event)
    {
        $participants = $event->getParticipants();
        
        if (!empty($participants)) {
            self::$originalParticipants[$event->eventID] = [
                'participantCount' => $event->participantCount,
                'participants' => $participants,
                'responses' => $event->getParticipantResponses()
            ];
        }
    }

    /**
     * Restores participant data after event update.
     * 
     * @param CalendarEventAction $eventObj The calendar event action object
     * @param array $objectIDs The event IDs being updated
     */
    private function restoreParticipantsAfterUpdate(CalendarEventAction $eventObj, array $objectIDs)
    {
        // Hook for extending classes to restore participants
        // This allows for custom participant preservation logic
        if (!empty(self::$originalParticipants)) {
            foreach ($objectIDs as $eventID) {
                if (isset(self::$originalParticipants[$eventID])) {
                    $this->applyParticipantsToEvent(
                        $eventID,
                        self::$originalParticipants[$eventID]
                    );
                }
            }

            // Clear the storage after restoration
            self::$originalParticipants = [];
        }
    }

    /**
     * Applies stored participant data back to an event.
     * 
     * @param int $eventID The event ID
     * @param array $participantData The participant data to restore
     */
    private function applyParticipantsToEvent($eventID, array $participantData)
    {
        try {
            $event = new CalendarEvent($eventID);
            if (!$event->eventID) {
                return;
            }

            // Update the event with preserved participant information
            // This ensures participant count and details are maintained
            $eventAction = new CalendarEventAction(
                [$event],
                'update',
                [
                    'data' => [
                        'participantCount' => $participantData['participantCount'] ?? 0
                    ]
                ]
            );
            $eventAction->executeAction();
        } catch (SystemException $e) {
            \wcf\functions\exception\logThrowable($e);
        }
    }

    /**
     * Retrieves stored participant data for a specific event.
     * 
     * @param int $eventID The event ID
     * @return array|null The stored participant data or null if not found
     */
    public static function getStoredParticipants($eventID)
    {
        return self::$originalParticipants[$eventID] ?? null;
    }

    /**
     * Clears all stored participant data.
     */
    public static function clearStoredParticipants()
    {
        self::$originalParticipants = [];
    }
}
