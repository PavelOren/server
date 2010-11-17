SELECT 
	COUNT(DISTINCT a.entry_id) count_all
FROM (
	SELECT ev.entry_id
		FROM	kalturadw.dwh_hourly_events_entry  ev , kalturadw.dwh_dim_entries en,
        (SELECT {TIME_SHIFT} time_shift, # time shift in hours
            {FROM_DATE_ID} start_date, # from date
            {TO_DATE_ID} end_date # to date
        ) p

	WHERE
	en.entry_id=ev.entry_id
	AND {OBJ_ID_CLAUSE}
	AND {SEARCH_TEXT_MATCH}
	AND {CATEGORIES_MATCH}	
	AND ev.partner_id = {PARTNER_ID} /* PARTNER_ID*/
    AND date_id BETWEEN calc_time_shift(start_date, 0, time_shift) AND calc_time_shift(end_date, 23, time_shift)
    AND calc_time_shift(date_id, hour_id, time_shift) between p.start_date AND p.end_date
	AND 
		( count_plays > 0 OR
		  count_edit > 0 OR
		  count_download > 0 OR
		  count_viral > 0 OR
		  count_report > 0 )
) AS a 