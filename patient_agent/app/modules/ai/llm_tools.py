import asyncio

from langchain_core.tools import tool

import app.config.constants as constants
# from utils import update_user_preference


@tool
def update_user_preferences_tool(preference_keys: list, preference_values: list) -> bool:
    """
    Updates the user's preferences, enabling the system to customize the response language and interaction mode according to the user's specified choice

    Args:
        preference_keys (list): A set of fixed keys representing user preferences.
            Valid keys are:
            - "language": Specifies the preferred language.
            - "modality": Defines the mode of interaction.

        preference_values (list): A set of corresponding values for the keys.
            Valid values for each key are:
            - "language": Can be "en" for English or "tw" for Twi.
            - "modality": Can be "text" or "speech".

    """


    try:
        user_preference = constants.user_preferences[constants.current_user_phone_number]
        print("user preference before llm update", user_preference)
        for i in range(len(preference_keys)):
            user_preference[preference_keys[i]] = preference_values[i].lower()

        # TODO: replace 'update_user_preference' with 'dal.insert'
        asyncio.create_task(update_user_preference(constants.current_user_phone_number, user_preference))

        print("user preference after llm update", user_preference)
        return True
    except IndexError as err:
        print("[ERROR] Could not find preference index ", err)
    except Exception as err:
        print("[ERROR] An error occurred while changing using preference: ", err)


available_tools_dict = {"update_user_preferences_tool": update_user_preferences_tool}
# tools_list = [update_user_preferences_tool]
tools_list = []