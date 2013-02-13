using Microsoft.Practices.SmartClient.DisconnectedAgent;
using System;
using Sante.EMR.SmartClient.OutBox.DataWebService;
using Microsoft.Practices.CompositeUI;

namespace Sante.EMR.SmartClient.OutBox.DSA.DataService
{
    // Generated code for the web service.
    // Use this proxy to make requests to the service when working in an application that is occasionally connected
    //[Service(typeof(Agent))]
    public partial class Agent
    {
        IRequestQueue requestQueue;

        public Agent(IRequestQueue requestQueue)
        {
            this.requestQueue = requestQueue;
        }

        #region HelloWorld

        /// <summary>
        /// Enqueues a request to the <c>HelloWorld</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid HelloWorld()
        {
            return HelloWorld(GetHelloWorldDefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>HelloWorld</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid HelloWorld(OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnHelloWorldReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnHelloWorldException");

            return EnqueueRequest("HelloWorld", behavior);
        }

        public static OfflineBehavior GetHelloWorldDefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnHelloWorldReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnHelloWorldException");

            return behavior;
        }

        #endregion HelloWorld

        #region HelloWorldAsync

        /// <summary>
        /// Enqueues a request to the <c>HelloWorldAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid HelloWorldAsync()
        {
            return HelloWorldAsync(GetHelloWorldAsync1DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>HelloWorldAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid HelloWorldAsync(OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnHelloWorldAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnHelloWorldAsync1Exception");

            return EnqueueRequest("HelloWorldAsync", behavior);
        }

        public static OfflineBehavior GetHelloWorldAsync1DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnHelloWorldAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnHelloWorldAsync1Exception");

            return behavior;
        }

        #endregion HelloWorldAsync

        #region HelloWorldAsync

        /// <summary>
        /// Enqueues a request to the <c>HelloWorldAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid HelloWorldAsync(Object userState)
        {
            return HelloWorldAsync(userState, GetHelloWorldAsync2DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>HelloWorldAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid HelloWorldAsync(Object userState, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnHelloWorldAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnHelloWorldAsync2Exception");

            return EnqueueRequest("HelloWorldAsync", behavior, userState);
        }

        public static OfflineBehavior GetHelloWorldAsync2DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnHelloWorldAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnHelloWorldAsync2Exception");

            return behavior;
        }

        #endregion HelloWorldAsync

        #region GetModifiedTables

        /// <summary>
        /// Enqueues a request to the <c>GetModifiedTables</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetModifiedTables()
        {
            return GetModifiedTables(GetGetModifiedTablesDefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>GetModifiedTables</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetModifiedTables(OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesException");

            return EnqueueRequest("GetModifiedTables", behavior);
        }

        public static OfflineBehavior GetGetModifiedTablesDefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesException");

            return behavior;
        }

        #endregion GetModifiedTables

        #region GetModifiedTablesAsync

        /// <summary>
        /// Enqueues a request to the <c>GetModifiedTablesAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetModifiedTablesAsync()
        {
            return GetModifiedTablesAsync(GetGetModifiedTablesAsync1DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>GetModifiedTablesAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetModifiedTablesAsync(OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesAsync1Exception");

            return EnqueueRequest("GetModifiedTablesAsync", behavior);
        }

        public static OfflineBehavior GetGetModifiedTablesAsync1DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesAsync1Exception");

            return behavior;
        }

        #endregion GetModifiedTablesAsync

        #region GetModifiedTablesAsync

        /// <summary>
        /// Enqueues a request to the <c>GetModifiedTablesAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetModifiedTablesAsync(Object userState)
        {
            return GetModifiedTablesAsync(userState, GetGetModifiedTablesAsync2DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>GetModifiedTablesAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetModifiedTablesAsync(Object userState, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesAsync2Exception");

            return EnqueueRequest("GetModifiedTablesAsync", behavior, userState);
        }

        public static OfflineBehavior GetGetModifiedTablesAsync2DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetModifiedTablesAsync2Exception");

            return behavior;
        }

        #endregion GetModifiedTablesAsync


        #region SendForms
        public Guid SendForms(Form form)
        {
            return SendForms(form, GetSendFormsDefaultBehavior());
        }

        /// <summary>
        /// 
        /// </summary>
        /// <returns></returns>
        private object GetSendFormsDefaultBehavior()
        {
            throw new Exception("The method or operation is not implemented.");
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="form"></param>
        /// <param name="p"></param>
        /// <returns></returns>
        private Guid SendForms(Form form, object p)
        {
            throw new Exception("The method or operation is not implemented.");
        }

        #endregion
        #region Search

        /// <summary>
        /// Enqueues a request to the <c>Search</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid Search(String firstname, String lastname, String phone, String address, String casenumber)
        {
            return Search(firstname, lastname, phone, address, casenumber, GetSearchDefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>Search</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid Search(String firstname, String lastname, String phone, String address, String casenumber, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnSearchReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnSearchException");

            return EnqueueRequest("Search", behavior, firstname, lastname, phone, address, casenumber);
        }

        public static OfflineBehavior GetSearchDefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnSearchReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnSearchException");

            return behavior;
        }

        #endregion Search

        #region SearchAsync

        /// <summary>
        /// Enqueues a request to the <c>SearchAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid SearchAsync(String firstname, String lastname, String phone, String address, String casenumber)
        {
            return SearchAsync(firstname, lastname, phone, address, casenumber, GetSearchAsync1DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>SearchAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid SearchAsync(String firstname, String lastname, String phone, String address, String casenumber, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnSearchAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnSearchAsync1Exception");

            return EnqueueRequest("SearchAsync", behavior, firstname, lastname, phone, address, casenumber);
        }

        public static OfflineBehavior GetSearchAsync1DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnSearchAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnSearchAsync1Exception");

            return behavior;
        }

        #endregion SearchAsync

        #region SearchAsync

        /// <summary>
        /// Enqueues a request to the <c>SearchAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid SearchAsync(String firstname, String lastname, String phone, String address, String casenumber, Object userState)
        {
            return SearchAsync(firstname, lastname, phone, address, casenumber, userState, GetSearchAsync2DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>SearchAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid SearchAsync(String firstname, String lastname, String phone, String address, String casenumber, Object userState, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnSearchAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnSearchAsync2Exception");

            return EnqueueRequest("SearchAsync", behavior, firstname, lastname, phone, address, casenumber, userState);
        }

        public static OfflineBehavior GetSearchAsync2DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnSearchAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnSearchAsync2Exception");

            return behavior;
        }

        #endregion SearchAsync

        #region GetTasks

        /// <summary>
        /// Enqueues a request to the <c>GetTasks</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetTasks(Int32 casenumber)
        {
            return GetTasks(casenumber, GetGetTasksDefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>GetTasks</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetTasks(Int32 casenumber, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetTasksReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetTasksException");

            return EnqueueRequest("GetTasks", behavior, casenumber);
        }

        public static OfflineBehavior GetGetTasksDefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetTasksReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetTasksException");

            return behavior;
        }

        #endregion GetTasks

        #region GetTasksAsync

        /// <summary>
        /// Enqueues a request to the <c>GetTasksAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetTasksAsync(Int32 casenumber)
        {
            return GetTasksAsync(casenumber, GetGetTasksAsync1DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>GetTasksAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetTasksAsync(Int32 casenumber, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetTasksAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetTasksAsync1Exception");

            return EnqueueRequest("GetTasksAsync", behavior, casenumber);
        }

        public static OfflineBehavior GetGetTasksAsync1DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetTasksAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetTasksAsync1Exception");

            return behavior;
        }

        #endregion GetTasksAsync

        #region GetTasksAsync

        /// <summary>
        /// Enqueues a request to the <c>GetTasksAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetTasksAsync(Int32 casenumber, Object userState)
        {
            return GetTasksAsync(casenumber, userState, GetGetTasksAsync2DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>GetTasksAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetTasksAsync(Int32 casenumber, Object userState, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetTasksAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetTasksAsync2Exception");

            return EnqueueRequest("GetTasksAsync", behavior, casenumber, userState);
        }

        public static OfflineBehavior GetGetTasksAsync2DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetTasksAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetTasksAsync2Exception");

            return behavior;
        }

        #endregion GetTasksAsync

        #region GetOPSCases

        /// <summary>
        /// Enqueues a request to the <c>GetOPSCases</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetOPSCases()
        {
            return GetOPSCases(GetGetOPSCasesDefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>GetOPSCases</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetOPSCases(OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesException");

            return EnqueueRequest("GetOPSCases", behavior);
        }

        public static OfflineBehavior GetGetOPSCasesDefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesException");

            return behavior;
        }

        #endregion GetOPSCases

        #region GetOPSCasesAsync

        /// <summary>
        /// Enqueues a request to the <c>GetOPSCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetOPSCasesAsync()
        {
            return GetOPSCasesAsync(GetGetOPSCasesAsync1DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>GetOPSCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetOPSCasesAsync(OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesAsync1Exception");

            return EnqueueRequest("GetOPSCasesAsync", behavior);
        }

        public static OfflineBehavior GetGetOPSCasesAsync1DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesAsync1Exception");

            return behavior;
        }

        #endregion GetOPSCasesAsync

        #region GetOPSCasesAsync

        /// <summary>
        /// Enqueues a request to the <c>GetOPSCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetOPSCasesAsync(Object userState)
        {
            return GetOPSCasesAsync(userState, GetGetOPSCasesAsync2DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>GetOPSCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid GetOPSCasesAsync(Object userState, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesAsync2Exception");

            return EnqueueRequest("GetOPSCasesAsync", behavior, userState);
        }

        public static OfflineBehavior GetGetOPSCasesAsync2DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnGetOPSCasesAsync2Exception");

            return behavior;
        }

        #endregion GetOPSCasesAsync

        #region RetrieveMCTCases

        /// <summary>
        /// Enqueues a request to the <c>RetrieveMCTCases</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveMCTCases()
        {
            return RetrieveMCTCases(GetRetrieveMCTCasesDefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>RetrieveMCTCases</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveMCTCases(OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesException");

            return EnqueueRequest("RetrieveMCTCases", behavior);
        }

        public static OfflineBehavior GetRetrieveMCTCasesDefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesException");

            return behavior;
        }

        #endregion RetrieveMCTCases

        #region RetrieveMCTCasesAsync

        /// <summary>
        /// Enqueues a request to the <c>RetrieveMCTCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveMCTCasesAsync()
        {
            return RetrieveMCTCasesAsync(GetRetrieveMCTCasesAsync1DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>RetrieveMCTCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveMCTCasesAsync(OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesAsync1Exception");

            return EnqueueRequest("RetrieveMCTCasesAsync", behavior);
        }

        public static OfflineBehavior GetRetrieveMCTCasesAsync1DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesAsync1Exception");

            return behavior;
        }

        #endregion RetrieveMCTCasesAsync

        #region RetrieveMCTCasesAsync

        /// <summary>
        /// Enqueues a request to the <c>RetrieveMCTCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveMCTCasesAsync(Object userState)
        {
            return RetrieveMCTCasesAsync(userState, GetRetrieveMCTCasesAsync2DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>RetrieveMCTCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveMCTCasesAsync(Object userState, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesAsync2Exception");

            return EnqueueRequest("RetrieveMCTCasesAsync", behavior, userState);
        }

        public static OfflineBehavior GetRetrieveMCTCasesAsync2DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveMCTCasesAsync2Exception");

            return behavior;
        }

        #endregion RetrieveMCTCasesAsync

        #region RetrieveIHITCases

        /// <summary>
        /// Enqueues a request to the <c>RetrieveIHITCases</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveIHITCases()
        {
            return RetrieveIHITCases(GetRetrieveIHITCasesDefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>RetrieveIHITCases</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveIHITCases(OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesException");

            return EnqueueRequest("RetrieveIHITCases", behavior);
        }

        public static OfflineBehavior GetRetrieveIHITCasesDefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesException");

            return behavior;
        }

        #endregion RetrieveIHITCases

        #region RetrieveIHITCasesAsync

        /// <summary>
        /// Enqueues a request to the <c>RetrieveIHITCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveIHITCasesAsync()
        {
            return RetrieveIHITCasesAsync(GetRetrieveIHITCasesAsync1DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>RetrieveIHITCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveIHITCasesAsync(OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesAsync1Exception");

            return EnqueueRequest("RetrieveIHITCasesAsync", behavior);
        }

        public static OfflineBehavior GetRetrieveIHITCasesAsync1DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesAsync1Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesAsync1Exception");

            return behavior;
        }

        #endregion RetrieveIHITCasesAsync

        #region RetrieveIHITCasesAsync

        /// <summary>
        /// Enqueues a request to the <c>RetrieveIHITCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveIHITCasesAsync(Object userState)
        {
            return RetrieveIHITCasesAsync(userState, GetRetrieveIHITCasesAsync2DefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>RetrieveIHITCasesAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid RetrieveIHITCasesAsync(Object userState, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesAsync2Exception");

            return EnqueueRequest("RetrieveIHITCasesAsync", behavior, userState);
        }

        public static OfflineBehavior GetRetrieveIHITCasesAsync2DefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesAsync2Return");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnRetrieveIHITCasesAsync2Exception");

            return behavior;
        }

        #endregion RetrieveIHITCasesAsync

        #region CancelAsync

        /// <summary>
        /// Enqueues a request to the <c>CancelAsync</c> web service method through the agent.
        /// </summary>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid CancelAsync(Object userState)
        {
            return CancelAsync(userState, GetCancelAsyncDefaultBehavior());
        }

        /// <summary>
        /// Enqueues a request to the <c>CancelAsync</c> web service method through the agent.
        /// </summary>
        /// <param name="behavior">The behavior associated with the offline request being enqueued.</param>
        /// <returns>The unique identifier associated with the request that was enqueued.</returns>
        public Guid CancelAsync(Object userState, OfflineBehavior behavior)
        {
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnCancelAsyncReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnCancelAsyncException");

            return EnqueueRequest("CancelAsync", behavior, userState);
        }

        public static OfflineBehavior GetCancelAsyncDefaultBehavior()
        {
            OfflineBehavior behavior = GetAgentDefaultBehavior();
            behavior.ReturnCallback = new CommandCallback(typeof(Callback), "OnCancelAsyncReturn");
            behavior.ExceptionCallback = new CommandCallback(typeof(Callback), "OnCancelAsyncException");

            return behavior;
        }

        #endregion CancelAsync

        #region Common

        public static OfflineBehavior GetAgentDefaultBehavior()
        {
            OfflineBehavior behavior = new OfflineBehavior();
            behavior.MaxRetries = 3;
            behavior.Stamps = 1;
            behavior.Expiration = DateTime.Now + new TimeSpan(1, 0, 0, 0);
            behavior.ProxyFactoryType = typeof(Microsoft.Practices.SmartClient.DisconnectedAgent.WebServiceProxyFactory);

            return behavior;
        }

        private Guid EnqueueRequest(string methodName, OfflineBehavior behavior, params object[] arguments)
        {
            Request request = CreateRequest(methodName, behavior, arguments);

            requestQueue.Enqueue(request);

            return request.RequestId;
        }

        private static Request CreateRequest(string methodName, OfflineBehavior behavior, params object[] arguments)
        {
            Request request = new Request();
            request.MethodName = methodName;
            request.Behavior = behavior;
            request.CallParameters = arguments;

            request.OnlineProxyType = OnlineProxyType;
            request.Endpoint = Endpoint;

            return request;
        }

        public static Type OnlineProxyType
        {
            get
            {
                return typeof(Sante.EMR.SmartClient.OutBox.DataWebService.DataService);
            }
        }

        public static string Endpoint
        {
            get
            {
                return String.Empty;
            }
        }
        #endregion
    }
}
